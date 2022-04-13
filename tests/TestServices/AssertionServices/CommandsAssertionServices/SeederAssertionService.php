<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class SeederAssertionService extends CommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Database\Seeders;',
                6 => 'class {{ name }}Seeder extends Seeder'
            ],
            [
                'name' => $this->setName($path, 'Seeder.php')
            ],
            $path
        );
    }
}
