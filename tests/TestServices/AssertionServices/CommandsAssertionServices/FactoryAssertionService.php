<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class FactoryAssertionService extends CommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Database\Factories;',
                7 => 'class {{ name }}Factory extends Factory',
                9 => 'protected string $model = {{ name }}::class;'
            ],
            [
                'name' => $this->setName($path, 'Factory.php')
            ],
            $path
        );
    }
}
