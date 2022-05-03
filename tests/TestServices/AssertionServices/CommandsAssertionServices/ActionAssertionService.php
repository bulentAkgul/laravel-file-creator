<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class ActionAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Actions'),
                4 => 'class {{ name }}',
                6 => 'public function __invoke(mixed $data)'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }

    public function static(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Actions'),
                4 => 'class {{ name }}',
                6 => 'public static function _(mixed $data)'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }
}
