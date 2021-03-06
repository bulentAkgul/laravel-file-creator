<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class CommandAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Commands'),
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
