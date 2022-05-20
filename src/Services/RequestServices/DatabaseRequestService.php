<?php

namespace Bakgul\FileCreator\Services\RequestServices;

use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Services\RequestService;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Pluralizer;
use Bakgul\Kernel\Helpers\Settings;

class DatabaseRequestService extends RequestService
{
    public function handle(array $request): array
    {
        return $this->extendRequest([
            'attr' => $request['attr'],
            'map' => $this->extendMap($request['map'], $request['attr'])
        ]);
    }

    private function extendMap(array $map, array $attr): array
    {
        return array_merge($map, [
            'table' => Convention::table($attr['name'], Pluralizer::set($attr['name_count'])),
        ]);
    }

    private function extendRequest(array $request): array
    {
        $request['map']['class'] = $this->makeReplacements($request, 'name_schema');
        $request['map']['namespace'] = $this->setNamespace($request);
        $request['map']['container'] = strtolower($request['map']['container']);
        $request['map']['model_namespace'] = $this->setModelNamespace($request['map']['root_namespace']);

        $request['attr']['path'] = $this->replace($request['map'], $request['attr']['path'], true);
        $request['attr']['file'] = "{$request['map']['class']}.php";

        return $request;
    }

    private function setModelNamespace($root)
    {
        return Path::glue(array_filter([
            Settings::standalone('laravel') ? 'App' : '',
            str_replace('Database', 'Models', $root)
        ]), '\\');
    }
}
