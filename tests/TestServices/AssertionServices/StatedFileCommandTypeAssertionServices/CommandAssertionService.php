<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class CommandAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Commands;',
                6 => 'class {{ name }}Command extends Command',
                10 => 'protected $description = '. "'Command description'" . ';'
            ],
            [
                'name' => $this->setName($path, 'Command.php')
            ],
            $path
        );
    }
}
