<?php

namespace Bakgul\FileCreator\Tests\TestServices\ExecutionServices;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\CollectTypes;
use Bakgul\Kernel\Tests\Tasks\SetupTest;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Tests\TestCase;
use Illuminate\Support\Str;

class FileTestService extends TestCase
{
    private $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    public function start($variation, $testType, $name = '', $extra = false, $append = '')
    {
        $this->testPackage = (new SetupTest)();

        $this->artisan($this->command($testType, $variation, $name, $extra, $append));

        $this->execute($variation, $testType, $name, $extra);
    }

    private function command(string $type, string $variation, string $name, $task, $append)
    {
        $task = $task == 'taskless' ? $task : (
            in_array($task, Settings::files("{$type}.tasks")) ? $task : ''
        );

        return "create:file "
            . $this->name($name, $task)
            . " {$type}"
            . Text::append($variation, ':')
            . " {$this->testPackage['name']}"
            . Text::append($append, ' ')
            . Text::append($task == 'taskless' ? '-t' : '', ' ');
    }

    private function name(string $name = '', array|bool|string $task = '')
    {
        return ($name ?: $this->file)
            . Text::append(is_string($task) && $task != 'taskless' ? $task : '', ':');
    }

    private function execute($variation, $testType, $name, $extra)
    {
        $asserter = new CommandsAssertionService;

        foreach ($this->setPaths($testType, $name, $extra) as $path) {
            $fullPath = Path::glue([$this->testPackage['path'], $path]);

            $this->assertFileExists($fullPath);

            $this->assertTrue(...$asserter->handle(
                $fullPath,
                $this->setType($testType, $variation, $path)
            ));
        }
    }

    private function setPaths(string $type, string $name, mixed $extra = false): array
    {
        return FilePathService::compose(CollectTypes::_($type), $name ?: $this->file, $extra);
    }

    protected function setType(string $testType, string $variation, string $path, string $file = ''): array
    {
        $path = Text::serialize($path);

        return [
            'name' => $n = Convention::class($path[$path[0] != 'tests']),
            'variation' => $this->setVariation($testType, $variation, $file, $n),
            'test_variation' => $variation
        ];
    }

    private function setVariation(string $testType, string $variation, string $file, string $type): string
    {
        return ConvertCase::camel($this->getVariation($testType, $variation, $file, $type));
    }

    private function getVariation(string $testType, string $variation, string $file, string $type)
    {
        if (strtolower(Str::singular($type)) == $testType) return $variation ?: 'default';

        return Arry::get(explode(':', $file), 2)
            ?: Settings::default('files', strtolower($type) . '.variations')
            ?: 'default';
    }
}
