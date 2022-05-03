<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class CastAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Casts'),
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
