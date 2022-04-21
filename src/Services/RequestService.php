<?php

namespace Bakgul\FileCreator\Services;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileCreator\FileCreator;
use Bakgul\FileCreator\Tasks\ExtendFileMap;
use Bakgul\Kernel\Tasks\ExtendMap;
use Bakgul\Kernel\Tasks\GenerateNamespace;
use Bakgul\Kernel\Tasks\GenerateAttr;
use Bakgul\Kernel\Tasks\GenerateMap;

class RequestService extends FileCreator
{
    public function handle(array $request): array
    {
        return [
            'attr' => $a = $this->generateAttr($request),
            'map' => $this->generateMap($a)
        ];
    }

    private function generateAttr(array $request)
    {
        return [...GenerateAttr::_($request), 'job' => 'file'];
    }

    private function generateMap(array $attr): array
    {
        return [
            ...GenerateMap::_($attr),
            ...ExtendMap::_(['attr' => $attr]),
            ...ExtendFileMap::_($attr),
            
        ];
    }

    protected function setNamespace(array $request): string
    {
        return GenerateNamespace::_($request['attr'], $this->makeReplacements($request, 'path_schema'));
    }

    protected function makeReplacements(array $request, string $key)
    {
        return $this->replace($request['map'], $request['attr'][$key], str_contains($key, 'path'));
    }

    protected function replace(array $map, string $value, bool $append = false)
    {
        return Text::replaceByMap($map, $value, $append);
    }
}