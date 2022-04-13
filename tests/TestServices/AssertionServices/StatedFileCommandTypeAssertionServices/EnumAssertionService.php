<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class EnumAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\\' . Settings::folders('enum') . ';',
                4 => 'enum {{ name }}'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }
}
