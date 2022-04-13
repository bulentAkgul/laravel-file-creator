<?php

namespace Bakgul\FileCreator\Services\RequestServices;

use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Services\RequestService;
use Carbon\Carbon;

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
            'table' => Convention::table($attr['name']),
            'date' => Carbon::today()->format('Y_m_d'),
            'pivot_columns' => ""
        ]);
    }

    private function extendRequest(array $request): array
    {
        $request['map']['class'] = $this->makeReplacements($request, 'name_schema');
        $request['map']['namespace'] = $this->setNamespace($request);
        $request['map']['container'] = strtolower($request['map']['container']);

        $request['attr']['path'] = $this->replace($request['map'], $request['attr']['path'], true);
        $request['attr']['file'] = "{$request['map']['class']}.php";

        return $request;
    }
}
