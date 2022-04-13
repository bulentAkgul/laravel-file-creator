<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class InterfaceAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\\' . Settings::folders('interface') . ';',
                4 => 'interface {{ name }}'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }
}
