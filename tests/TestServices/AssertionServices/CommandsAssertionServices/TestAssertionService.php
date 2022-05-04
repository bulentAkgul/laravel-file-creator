<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class TestAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->{Settings::default('files', 'test.variations')}($path, $rootNamespace);
    }
    
    public function feature(string $path, string $rootNamespace): array
    {
        $name = $this->setName($path, 'Test.php');
        $task = $this->setTask($name);

        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'tests', 'Feature' . ($task ? '\{{ name }}Tests' : '')),
                6 => $this->setNamespace($rootNamespace, 'tests', 'TestCase', 'use') . ';',
                8 => 'class {{ task }}{{ name }}Test extends TestCase'
            ],
            [
                'name' => str_replace($task, '', $name),
                'task' => $task,
            ],
            $path
        );
    }

    public function unit(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'tests', 'Unit'),
                4 => 'use PHPUnit\Framework\TestCase;',
                6 => 'class {{ name }}Test extends TestCase'
            ],
            [
                'name' => $this->setName($path, 'Test.php')
            ],
            $path
        );
    }
}
