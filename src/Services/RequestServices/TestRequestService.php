<?php

namespace Bakgul\FileCreator\Services\RequestServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\FileCreator\Services\RequestService;

class TestRequestService extends RequestService
{
    public function handle(array $request): array
    {
        return $this->extendRequest([
            'attr' => $a = $this->extendAttr($request['attr']),
            'map' => $this->extendMap($request['map'], $a)
        ]);
    }

    private function extendAttr(array $attr): array
    {
        return array_merge($attr, [
            'task' => $this->setTasks($attr),
        ]);
    }

    public function extendMap(array $map, array $attr): array
    {
        return array_merge($map, [
            'task' => $attr['variation'] == 'feature' ? $map['task'] : '',
            'variation' => ucfirst($attr['variation']),
            'folder' => $attr['variation'] == 'feature' &&  $attr['task'] ? Folder::name($attr['name'], $attr['type']) : '',
        ]);
    }

    private function setTasks($attr)
    {
        return $this->isTaskable($attr) ? $attr['task'] : '';
    }

    private function isTaskable(array $attr)
    {
        return $attr['variation'] == 'feature'
            && in_array($attr['task'], Settings::files("{$attr['type']}.tasks"));
    }

    private function extendRequest(array $request): array
    {
        $request['attr']['path'] = $this->makeReplacements($request, 'path');

        $request['map']['class'] = $this->makeReplacements($request, 'name_schema');
        $request['map']['namespace'] = $this->setNamespace($request);

        $request['attr']['file'] = "{$request['map']['class']}.php";

        return $request;
    }
}
