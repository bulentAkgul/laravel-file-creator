<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Illuminate\Support\Str;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class MigrationAssertionService extends StatedFileCommandsAssertionService
{
    public function create(string $path): array
    {
        return $this->default($path);
    }

    public function default(string $path): array
    {
        return $this->assert(
            [
                6 => 'return new class extends Migration',
                10 => "Schema::create('{{ name }}', function (Blueprint " . '$table) {',
            ],
            [
                'name' => Str::between($path, 'create_', '_table')
            ],
            $path
        );
    }

    public function update(string $path): array
    {
        return $this->assert(
            [
                6 => 'return new class extends Migration',
                10 => "Schema::{{ method }}('{{ name }}', function (Blueprint " . '$table) {',
            ],
            [
                'name' => $n = Str::between($path, 'create_', '_table'),
                'method' => $n == 'users' ? 'create' : 'table'
            ],
            $path
        );
    }
}
