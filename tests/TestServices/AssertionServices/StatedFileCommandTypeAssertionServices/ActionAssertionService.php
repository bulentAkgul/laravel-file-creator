<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class ActionAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Actions;',
                4 => 'class {{ name }}',
                6 => 'public function __invoke(mixed $data)'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }

    public function static(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Actions;',
                4 => 'class {{ name }}',
                6 => 'public static function _(mixed $data)'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }
}
