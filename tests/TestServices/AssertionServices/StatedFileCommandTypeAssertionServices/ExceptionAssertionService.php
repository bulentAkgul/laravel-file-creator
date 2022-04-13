<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class ExceptionAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Exceptions;',
                6 => 'class {{ name }}Exception extends Exception'
            ],
            [
                'name' => $this->setName($path, 'Exception.php')
            ],
            $path
        );
    }
}
