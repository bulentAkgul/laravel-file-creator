<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class JobAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Jobs'),
                6 => 'class {{ name }}Job',
                8 => 'use Dispatchable;'
            ],
            [
                'name' => $this->setName($path, 'Job.php')
            ],
            $path
        );
    }
}
