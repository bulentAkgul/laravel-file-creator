<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\Kernel\Helpers\Arry;

class ServiceAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace, array $extra): array
    {
        $name = $this->setName($path, 'Service.php');
        $task = $this->setTask($name);

        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Services' . ($task ? '\{{ name }}Services' : '')),
                4 => 'class {{ task }}{{ name }}Service',
                6 => 'public function handle({{ args }})'
            ],
            [
                'name' => $n = str_replace($task, '', $name),
                'task' => $task,
                'args' => $this->setServiceArgs($task, $n, $extra)
            ],
            $path
        );
    }

    private function setServiceArgs(string $task, string $name, array $extra): string
    {
        $parent = Arry::get($extra, 'parent')
            ? Arry::get(explode('-p=', $extra['append']), 1) ?? ''
            : '';

        return implode(', ', array_map(fn ($x) => '$' . $x, array_filter([
            in_array($task, ['Store', 'Update']) ? 'request' : '',
            str_contains($extra['type']['test_variation'], 'nested') ? Convention::var($parent) : '',
            Convention::var($name)
        ])));
    }
}
