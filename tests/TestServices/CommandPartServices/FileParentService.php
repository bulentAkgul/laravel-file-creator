<?php

namespace Bakgul\FileCreator\Tests\TestServices\CommandPartServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\CommandServices\FileCommandService;
use Illuminate\Support\Arr;

class FileParentService extends FileCommandService
{
    public function __invoke(array $commandCases): array
    {
        $variation = $this->getParentRequiredVariation($commandCases['type']);

        $commandCases['parent']['value'] = $variation ? $this->setParent($commandCases, $variation) : null;
        
        return $commandCases;
    }

    private function getParentRequiredVariation($type)
    {
        return Arry::get(array_intersect($this->getVariations($type), array_keys(Settings::main('need_parent'))), 0);
    }

    private function getVariations($type)
    {
        return array_map(fn ($x) => $x, Arr::pluck($type['value'], 'variation'));
    }

    private function setParent(array $commandCases, string $variation)
    {
        return [
            'name' => $this->setPart(1, 1, uniques: $commandCases['name']['value']['all'])[0],
            'type' => $this->setType($commandCases['type']['type'], $variation),
            'variation' => $this->setVariation($variation),
        ];
    }

    private function setType(string $type, string $variation)
    {
        $needParent = Settings::main("need_parent");

        return in_array(
            $needParent[$variation],
            array_keys(Settings::files())
        ) ? $needParent[$variation] : $type;
    }

    private function setVariation(string $variation)
    {
        $needParent = Settings::main("need_parent");

        return in_array(
            $needParent[$variation],
            array_keys(Settings::files())
        ) ? '' : $needParent[$variation];
    }
}
