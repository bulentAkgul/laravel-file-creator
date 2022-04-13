<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class CastAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Casts;',
                6 => 'class {{ name }}Cast implements CastsAttributes',
                13 => 'public function set($model, string $key, mixed $value, array $attributes): mixed'
            ],
            [
                'name' => $this->setName($path, 'Cast.php')
            ],
            $path
        );
    }
}
