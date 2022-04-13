<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class SeederAssertionService extends StatedFileCommandsAssertionService
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
