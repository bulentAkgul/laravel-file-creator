<?php

namespace Bakgul\FileCreator\Tests\TestServices\CommandServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Tasks\SerializeSignature;
use Bakgul\Kernel\Tests\Services\TestDataService;
use Bakgul\FileCreator\Commands\CreateFileCommand;
use Bakgul\FileCreator\Tests\TestContracts\CommandGenerator;
use Bakgul\FileCreator\Tests\TestServices\CommandPartServices\FileAppService;
use Bakgul\FileCreator\Tests\TestServices\CommandPartServices\FileNameService;
use Bakgul\FileCreator\Tests\TestServices\CommandPartServices\FileParentService;
use Bakgul\FileCreator\Tests\TestServices\CommandPartServices\FileTaskService;
use Bakgul\FileCreator\Tests\TestServices\CommandPartServices\FileTypeService;
use Bakgul\FileCreator\Tests\TestTasks\StringifyCommand;
use Illuminate\Support\Arr;

class FileCommandService implements CommandGenerator
{
    public function getSignature(): array
    {
        return SerializeSignature::_((new CreateFileCommand)->getSignature());
    }

    public function generate(array $commandCases, array $specs): array
    {
        foreach (array_keys($commandCases) as $part) {
            $commandCases[$part] = $this->initPartGenerator($part, $commandCases, $specs);
        }

        return $this->convertToCommand($this->completeCommand($commandCases));
    }

    private function initPartGenerator(string $part, array $commandCases, array $specs)
    {
        return match($part) {
            'name' => (new FileNameService)($commandCases['name']),
            'type' => (new FileTypeService)($commandCases['type'], $specs),
            'app' => (new FileAppService)($commandCases['app']),
            default => $commandCases[$part]
        };
    }

    private function completeCommand($commandCases): array
    {
        $commandCases = (new FileParentService)($commandCases);
        $commandCases = (new FileTaskService)($commandCases);

        return $commandCases;
    }

    private function convertToCommand($commandCases): array
    {
        return [
            'arr' => $a = $this->prepare($commandCases),
            'str' => (new StringifyCommand)($a)
        ];
    }

    private function prepare(array $commandCases): array
    {
        return array_map(fn ($x) => $this->setCase($x), $commandCases);
    }

    private function setCase(array $case): array
    {
        $case['value'] = Arry::has('value', $case) ? $case['value'] : $case['specs'];
        
        unset($case['specs']);
        
        return $case;
    }

    protected function setPart(int $max, int $min, array $source = [], array ...$uniques)
    {
        $length = count(Arry::range($max, $min));
        
        if ($length == 0) return [];

        $source = array_diff(
            empty($source) ? TestDataService::words() : $source,
            Arr::flatten($uniques)
        );

        return $length < count($source) ? Arry::random($source, $length) : $source;
    }
}
