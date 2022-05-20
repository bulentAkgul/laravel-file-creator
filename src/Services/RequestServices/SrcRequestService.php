<?php

namespace Bakgul\FileCreator\Services\RequestServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Pluralizer;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Services\RequestService;
use Bakgul\FileCreator\Tasks\ModifyFilePointer;

class SrcRequestService extends RequestService
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
            'task' => $this->setTask($attr),
        ]);
    }

    private function extendMap(array $map, array $attr): array
    {
        return array_merge($map, [
            'api' => str_contains($attr['variation'], 'api') ? Settings::folders('api') : '',
            'parent' => $this->setParent($attr),
            'parent_var' => Convention::var($attr['parent']['name']),
            'var' => $this->setVar($attr),
            'folder' => $this->setFolder($attr),
            'request' => $this->setMapName($attr, 'request'),
            'service' => $this->setMapName($attr, 'service'),
            'view' => 'Views',
            'wrapper' => 'Http',
        ]);
    }

    private function setParent(array $attr): string
    {
        return ConvertCase::_(
            $attr['parent']['name'],
            Arry::get($attr, 'convention') ?? 'pascal',
            Pluralizer::set($attr['name_count']),
        );
    }

    private function setVar(array $attr): string
    {
        $var = Convention::var($attr['name']);

        return $attr['type'] == 'policy' && $var == 'user' ? 'policyUser' : $var;
    }

    private function setFolder(array $attr): string
    {
        return $this->isFolderless($attr) ? '' : Folder::name($attr['name'], $attr['type']);
    }

    private function isFolderless(array $attr): bool
    {
        return count(Settings::files("{$attr['type']}.tasks")) > 1 && $attr['task'] == '';
    }

    private function extendRequest(array $request): array
    {
        $request['map']['user_model'] = $this->makeUserClass($request);
        $request['map']['class'] = $this->makeReplacements($request, 'name_schema');
        $request['map']['namespace'] = $this->setNamespace($request);
        $request['map']['db_namespace'] = $this->setDbNamespace($request);

        $request['attr']['path'] = $this->makeReplacements($request, 'path');
        $request['attr']['file'] = "{$request['map']['class']}.php";

        return $this->modifyPointers($request);
    }

    private function makeUserClass(array $request): string
    {
        $model = Arry::get(Settings::requires(
            callback: fn ($x) => $x['name'] == 'user' && $x['type'] == 'model'
        ), 0);

        return $request['map']['name'] != 'User' && $model
            ? "use {$model['class']};" . PHP_EOL
            : '';
    }

    private function setDbNamespace($request): string
    {
        return "{$request['map']['root_namespace']}\Database";
    }

    private function modifyPointers(array $request)
    {
        $request['map']['namespace'] = ModifyFilePointer::namespace($request);
        $request['attr']['path'] = ModifyFilePointer::path($request);

        return $request;
    }

    private function setTask($request)
    {
        return in_array($request['task'], Settings::files("{$request['type']}.tasks")) ? $request['task'] : '';
    }

    private function setMapName(array $attr, string $type = ''): string
    {
        return Convention::class($attr['name'], Pluralizer::set($type ? Settings::files($type) : $attr));
    }
}
