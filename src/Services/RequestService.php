<?php

namespace Bakgul\FileCreator\Services;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Pluralizer;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileCreator\FileCreator;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Tasks\GenerateNamespace;
use Bakgul\Kernel\Tasks\SetRequestAttr;

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
        return [...SetRequestAttr::_($request), 'job' => 'file'];
    }

    private function generateMap(array $attr): array
    {
        return [
            'package' => $attr['package'],
            'app' => $this->setApp($attr),
            'family' => $attr['family'],
            'name' => $this->setValue($attr['name'], $attr['convention']),
            'suffix' => Convention::affix($attr['type']),
            'task' => Convention::affix(Arry::get($attr, 'task') ?? ''),
            'root_namespace' => GenerateNamespace::_($attr),
            'container' => $this->setValue($this->getContainer($attr['type'])),
            'subs' => $this->setSubs($attr['subs']),
            'table' => '',
        ];
    }

    private function setApp(array $attr)
    {
        return str_contains($attr['path_schema'], '{{ app }}') ? $this->setValue($attr['app_folder']) : '';
    }

    private function getContainer(string $key): string
    {
        return Folder::get($key);
    }

    private function setSubs(array $subs): string
    {
        return Path::glue(Path::make($subs));
    }

    protected function setValue(string $value, string $case = 'pascal'): string
    {
        return Arry::get(Path::make($value, case: $case), 0) ?? '';
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