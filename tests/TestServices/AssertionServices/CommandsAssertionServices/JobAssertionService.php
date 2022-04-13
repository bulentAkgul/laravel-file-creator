<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class JobAssertionService extends CommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Jobs;',
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
