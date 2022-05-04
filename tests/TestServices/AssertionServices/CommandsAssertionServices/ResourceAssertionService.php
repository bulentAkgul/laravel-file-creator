<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class ResourceAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Resources', wrap: 'Http'),
                6 => 'use JsonSerializable;',
                8 => 'class {{ name }}Resource extends JsonResource',
                10 => 'public function toArray($request): array|Arrayable|JsonSerializable'
            ],
            [
                'name' => $this->setName($path, 'Resource.php')
            ],
            $path
        );
    }

    public function collection(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Resources', wrap: 'Http'),
                5 => 'use Illuminate\Http\Resources\Json\ResourceCollection;',
                8 => 'class {{ name }}Resource extends ResourceCollection',
                10 => 'public function toArray($request): array|Arrayable|JsonSerializable'
            ],
            [
                'name' => $this->setName($path, 'Resource.php')
            ],
            $path
        );
    }
}
