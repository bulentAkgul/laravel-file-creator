<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class ModelAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Models'),
                7 => 'class {{ name }} extends Model'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }

    public function pivot(string $path, string $rootNamespace): array
    {
        $name = $this->setName($path, '.php');

        return $this->assert(array_replace(
            [2 => $this->setNamespace($rootNamespace, 'src', 'Models'),],
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
