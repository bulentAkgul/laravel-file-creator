<?php

namespace Bakgul\FileCreator\Services\RequestServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\FileCreator\Services\RequestService;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Helpers\Text;

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
            'folder' => $this->setFolder($attr)
        ]);
    }

    private function setFolder($attr)
    {
        return match (true) {
            $this->hasTask($attr) => Folder::name($attr['name'], $attr['type']),
            $this->isIsolated($attr) => Settings::folders('class-tests'),
            default => ''
        };
    }

    private function hasTask($attr): bool
    {
        return $attr['variation'] == 'feature' &&  $attr['task'];
    }

    private function isIsolated($attr): bool
    {
        return count(array_filter($attr['queue'], fn ($x) => $x['type'] == 'test' && $x['status'] != 'main')) == 1
            && Arry::value($attr['queue'], 'main', 'status', '=', 'type') != 'controller';
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
        $request['map']['subs'] = $this->extendSubs($request);
        $request['attr']['path'] = $this->makeReplacements($request, 'path');

        $request['map']['class'] = $this->makeReplacements($request, 'name_schema');
        $request['map']['namespace'] = $this->setNamespace($request);

        $request['attr']['file'] = "{$request['map']['class']}.php";

        return $request;
    }

    private function extendSubs($request)
    {
        return Text::prepend(
            $this->isIsolated($request['attr'])
                ? "{$this->getMainType($request)}Tests"
                : ''
        ) . $request['map']['subs'];
    }

    private function getMainType($request)
    {
        return Convention::class(Arry::value($request['attr']['queue'], 'main', 'status', '=', 'type'));
    }
}
