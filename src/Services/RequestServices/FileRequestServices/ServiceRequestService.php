<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;

class ServiceRequestService extends SrcRequestService
{
    public function __invoke(array $request): array
    {
        $request['attr']['parent'] = $this->setParent($request['attr']);

        $request['map']['args'] = $this->setArgs($request);
        $request['map']['model'] = $this->setModel($request['map']);
        
        return $request;
    }

    private function setParent($attr)
    {
        if ($attr['status'] != 'pair') return $attr['parent'];

        $main = array_values(array_filter($attr['queue'], function ($x) use ($attr) {
            return $x['status'] == 'main'
                && $x['name'] == $attr['name']
                && in_array('service', Settings::files("{$x['type']}.pairs"));
        }));

        if (empty($main)) return $attr['parent'];

        return [
            'name' => $main[0]['name'],
            'type' => $main[0]['type'],
            'variation' => $main[0]['variation'],
            'grandparent' => str_contains($main[0]['variation'], 'nested') ? $attr['command']['parent'] : ''
        ];
    }

    private function setArgs(array $request): string
    {
        return implode(', ', array_map(fn ($x) => '$' . $x, array_filter([
            in_array($request['attr']['task'], ['store', 'update']) ? 'request' : '',
            str_contains($request['attr']['parent']['variation'], 'nested') ? $request['attr']['parent']['grandparent'] : '',
            $request['map']['var']
        ])));
    }

    private function setModel($map)
    {
        return str_contains($map['args'], '$' . $map['var'])
            ? ''
            : Text::inject($this->replace($map, 'use {{ root_namespace }}\Models\{{ name }};'), PHP_EOL);
    }
}
