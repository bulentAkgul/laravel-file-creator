<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Text;

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
                2 => $this->namespace($rootNamespace, $path),
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

    private function namespace($rn, $path)
    {
        $body = Text::dropTail(explode('tests' . DIRECTORY_SEPARATOR, $path)[1]);

        return 'namespace ' . Path::glue(array_filter(
            [$rn, 'Tests', Path::toNamespace($body)]
        ), '\\') . ';';
    }
}
