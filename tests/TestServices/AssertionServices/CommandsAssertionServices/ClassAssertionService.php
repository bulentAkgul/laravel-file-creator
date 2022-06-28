<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\Kernel\Helpers\Convention;

class ClassAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace, array $extra): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', $this->convertTail($extra['subs'])),
                4 => 'class {{ name }}',
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }

    public function static(string $path, string $rootNamespace, array $extra): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', $this->convertTail($extra['subs'])),
                4 => 'class {{ name }}',
                6 => 'public static function _()'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }

    public function invokable(string $path, string $rootNamespace, array $extra): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', $this->convertTail($extra['subs'])),
                4 => 'class {{ name }}',
                6 => 'public function __invoke()'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }
}
