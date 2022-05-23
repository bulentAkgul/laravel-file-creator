<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;

class ComponentRequestService extends SrcRequestService
{
    public function __invoke(array $request): array
    {
        $request['attr']['path'] = $this->changeTail($request, 'path');

        $request['map']['namespace'] = $this->changeTail($request, 'namespace');
        $request['map']['subs'] = $this->changeTail($request, 'subs');
        $request['map']['path'] = $this->setPath($request['map']);
        $request['map']['class'] = $this->setClass($request, 'pascal');
        $request['map']['name'] = $this->setName($request);

        $request['attr']['file'] = "{$request['map']['class']}.php";

        return $request;
    }

    private function changeTail($request, $key)
    {
        $src = $key == 'path' ? $request['attr'] : $request['map'];

        return $request['map']['subs'] == 'Roots'
            ? Text::changeTail($src[$key], 'Layouts', $key == 'namespace' ? '\\' : DIRECTORY_SEPARATOR)
            : $src[$key];
    }

    private function setPath(array $map): string
    {
        if ($map['subs'] == 'Layouts') return '';

        return implode('.', array_filter(array_map(
            fn ($x) => ConvertCase::_($x, Settings::resources('blade.convention') ?? 'kebab'),
            explode('.', str_replace(DIRECTORY_SEPARATOR, '.', "{$map['subs']}.{$map['name']}"))
        )));
    }

    private static function setClass(array $request): string
    {
        return $request['map']['class'] . ($request['map']['subs'] == 'Layouts' ? 'Layout' : '');
    }

    private function setName(array $request): string
    {
        $name = ConvertCase::_($request['attr']['name'], Settings::resources('blade.convention') ?? 'kebab');

        return $request['map']['subs'] == 'Layouts'
            ? $name
            : $this->append($name);
    }

    private function append($str)
    {
        return Text::append($str, '.');
    }
}
