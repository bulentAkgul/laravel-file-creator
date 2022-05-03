<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class ExceptionAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Exceptions'),
                6 => 'class {{ name }}Exception extends Exception'
            ],
            [
                'name' => $this->setName($path, 'Exception.php')
            ],
            $path
        );
    }
}
