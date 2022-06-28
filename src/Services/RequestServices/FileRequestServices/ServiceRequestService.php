<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\FileCreator\Tasks\IsPairFile;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Tasks\FindModel;

class ServiceRequestService extends SrcRequestService
{
    public function __invoke(array $request): array
    {
        $request['map'] = $this->setParent($request);

        $request['map']['args'] = $this->setArgs($request);
        $request['map']['model'] = $this->setModel($request);

        return $request;
    }

    private function setParent($request)
    {
        if ($this->isParentless($request['attr'])) {
            $request['map']['parent'] = '';
            $request['map']['parent_var'] = '';
        }

        return $request['map'];
    }

    private function isParentless(array $attr)
    {
        return $attr['status'] != 'pair'
            || Text::hasNot(Isolation::variation($attr['command']['type']), 'nested');
    }

    private function setArgs(array $request): string
    {
        if ($this->isNotPair($request)) return 'mixed $data';

        return implode(', ', array_filter([
            $this->setRequestArg($request['attr']),
            $this->setParentArg($request['map']),
            $this->setMainArg($request),
        ]));
    }

    private function isNotPair(array $request)
    {
        return !IsPairFile::_('service', $request['attr']['queue']);
    }

    private function setRequestArg(array $attr): string
    {
        return in_array($attr['task'], ['store', 'update']) ? 'array $request' : '';
    }

    private function setParentArg(array $map): string
    {
        return $map['parent'] ? $map['parent'] . ' $' . $map['parent_var'] : '';
    }

    private function setMainArg(array $request): string
    {
        return (in_array($request['attr']['task'], ['edit', 'update', 'destroy']) ? $request['map']['name'] : 'string') . ' $' . $request['map']['var'];
    }

    private function setModel(array $request): string
    {
        if ($this->isNotPair($request)) return '';

        return Text::wrap(implode(PHP_EOL, Arry::sort(array_map(
            fn ($x) => "use {$x}",
            $this->getModels($request['map'])
        ))), PHP_EOL);
    }

    private function getModels(array $map): array
    {
        return array_filter([$this->mainModel($map), $this->parentModel($map)]);
    }

    private function mainModel(array $map)
    {
        return str_contains($map['args'], 'string $' . $map['var']) ? '' : $this->model('name', $map);
    }

    private function parentModel(array $map)
    {
        return $map['parent'] ? ($this->findParent($map) ?: $this->model('parent', $map)) : '';
    }

    private function findParent(array $map): string
    {
        $model = FindModel::_($map['parent']);

        return $model ? trim(str_replace(['namespace', ';'], ['', '\\'], file($model)[2])) . "{$map['parent']};" : '';
    }

    private function model(string $key, array $map): string
    {
        return $this->replace($map, "{{ root_namespace }}\Models\{{ {$key} }};");
    }
}
