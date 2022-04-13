<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class ModelAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Models;',
                7 => 'class {{ name }} extends Model'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }

    public function pivot(string $path): array
    {
        $name = $this->setName($path, '.php');

        return $this->assert(array_replace(
            [2 => 'namespace CurrentTest\Testing\Models;'],
            $this->setLines($name)
        ),
            [
                'name' => $name,
                'extends' => $name == 'User' ? 'Model' : 'Pivot'
            ],
            $path
        );
    }

    private function setLines(string $name): array
    {
        return [
            4 => $name != 'User' ? 'use Illuminate\Database\Eloquent\Relations\Pivot;' : 'use Illuminate\Database\Eloquent\Factories\HasFactory;',
            6 => $name != 'User' ? 'class {{ name }} extends {{ extends }}' : '',
            7 => $name != 'User' ? '{' : 'class {{ name }} extends Model'
        ];
    }
}
