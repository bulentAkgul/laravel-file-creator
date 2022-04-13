<?php

namespace Bakgul\FileCreator\Tests\TestServices\ExecutionServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Tests\TestCase;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Tests\TestServices\AssertionService;
use Bakgul\FileCreator\Tests\TestServices\CommandService;
use Bakgul\FileCreator\Tests\TestServices\ExpectationService;
use Illuminate\Support\Str;

class FileTests extends TestCase
{
    protected function test(array $case, array $specs): array
    {
        $commands = $this->makeCommands($case, $specs);

        foreach ($commands as $command) {
            $this->artisan("create:" . strtolower($specs['create']) . " {$command['str']}");

            $assertions = (new AssertionService(
                new $specs['assertion'],
                $this->expect($command, $specs)
            ))();

            if ($assertions[0] !== true) return $assertions;
        }

        return $assertions;
    }

    private function makeCommands(array $case, array $specs)
    {
        return (new CommandService(new $specs['command'], $case, $specs))();
    }

    private function expect(array $command, array $specs)
    {
        return (new ExpectationService(new $specs['expectation'], $command))();
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

    protected function fileTypes($type, $types = [])
    {
        $types[] = $type;

        foreach ($this->pairs($type, $types) as $pair) {
            $types[] = $pair;
            
            if (empty(array_filter(Settings::files("{$pair}.pairs")))) continue;

            $types = array_merge($types, $this->fileTypes($pair, $types));
        }

        return Arry::unique($types);
    }

    private function pairs($type, $types)
    {
        return array_filter(array_diff(Settings::files("{$type}.pairs"), $types));
    }
}