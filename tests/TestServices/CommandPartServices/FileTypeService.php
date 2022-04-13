<?php

namespace Bakgul\FileCreator\Tests\TestServices\CommandPartServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\CommandServices\FileCommandService;
use Illuminate\Support\Arr;

class FileTypeService extends FileCommandService
{
    const PARTS = ['type', 'variation'];

    public function __invoke(array $case, array $specs): array
    {
        $case['value'] = [];

        $fileSpecs = $this->getFileSpecs($specs['family']);

        foreach ($this->setPart($case['specs'], 1, array_keys($fileSpecs)) as $type) {
            $case['value'][] = [
                'type' => $type,
                'variation' => $this->setVariation($case['value'], $fileSpecs[$type]['variations'], $type),
            ];
        }

        return $case;
    }

    private function getFileSpecs(string $family)
    {
        return Settings::files(callback: fn ($x) => $x['family'] == $family);
    }

    private function setVariation($generatedTypes, $variations, $type)
    {
        return $type == 'provider' ? '' : Arry::random(array_diff(
            $variations, $this->getParentRequiredVariation($generatedTypes)
        ));
    }

    private function getParentRequiredVariation($generatedTypes)
    {
        return array_intersect(
            Arr::pluck($generatedTypes, 'variation'),
            array_keys(Settings::main('need_parent'))
        );
    }
}
