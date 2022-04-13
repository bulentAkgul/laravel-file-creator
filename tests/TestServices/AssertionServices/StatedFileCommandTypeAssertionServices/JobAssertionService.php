<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class JobAssertionService extends StatedFileCommandsAssertionService
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
