<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class SeederAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'database', 'Seeders'),
                6 => 'class {{ name }}Seeder extends Seeder'
            ],
            [
                'name' => $this->setName($path, 'Seeder.php')
            ],
            $path
        );
    }
}
