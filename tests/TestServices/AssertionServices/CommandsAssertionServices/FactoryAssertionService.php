<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class FactoryAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'database', 'Factories'),
                7 => 'class {{ name }}Factory extends Factory',
                9 => 'public function modelName()',
                11 => 'return {{ name }}::class;'
            ],
            [
                'name' => $this->setName($path, 'Factory.php')
            ],
            $path
        );
    }
}
