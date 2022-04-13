<?php

namespace Bakgul\FileCreator\Tests\TestServices\ExpectationServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Tests\Tasks\CreateFileList;
use Bakgul\FileCreator\Tests\TestContracts\GenerateExpectation;
use Bakgul\FileCreator\Tests\TestServices\ExpectationServices\FileExpectationServices\DatabaseExpectationService;
use Bakgul\FileCreator\Tests\TestTasks\SetNamespace;
use Bakgul\FileCreator\Tests\TestTasks\SetPath;
use Illuminate\Support\Str;

class FileExpectationService implements GenerateExpectation
{
    private array $command;
    private array $specs;
    private array $file;

    public function handle(array $command): array
    {
        $this->command = $command;

        $expectations = [];

        foreach ((new CreateFileList)($command['arr']) as $file) {
            $this->specs = Settings::files($file['type']);
            $this->file = $file;

            $expectation = $this->setExpectations();

            if ($this->specs['family'] == 'database')
                $expectation = (new DatabaseExpectationService($file, $expectation))();

            $expectations[] = $expectation;
        }

        return $expectations;
    }

    private function setExpectations()
    {
        return [
            'file' => $this->file,
            'class' => $c = $this->makeClass(),
            'path' => $p = $this->makePath($c),
            'namespace' => $this->makeNamespace($p),
        ];
    }

    protected function makeClass(): string
    {
        return $this->setTask() . $this->setName() . $this->setSuffix();
    }

    private function setTask(): string
    {
        return str_contains($this->specs['name_schema'], '%TASK%') ? ucfirst($this->file['task']) : '';
    }

    protected function setName(): string
    {
        return ConvertCase::_(
            $this->file['name'],
            Arry::get($this->specs, 'convention') ?? 'pascal',
            $this->isSingular()
        ) . $this->addService();
    }

    protected function isSingular(): ?bool
    {
        return ['P' => false, 'S' => true, 'X' => null][$this->specs['name_count']];
    }

    private function addService()
    {
        return $this->specs['family'] == 'src' && str_contains($this->specs['name_schema'], 'Service') ? 'Service' : '';
    }

    private function setSuffix(): string
    {
        return str_contains($this->specs['name_schema'], '%SUFFIX%') ? Str::singular(ucfirst($this->file['type'])) : '';
    }

    protected function makePath(string $class): string
    {
        return (new SetPath($this->specs, $this->file, $this->command, $class))();
    }

    protected function makeNamespace(string $path)
    {
        return (new SetNamespace($this->specs['family'], $path))();
    }
}
