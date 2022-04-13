<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class ResourceAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Resources;',
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

    public function collection(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Resources;',
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
