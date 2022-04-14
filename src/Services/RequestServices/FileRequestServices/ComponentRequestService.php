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
        $request['map']['path'] = $this->setPath($request);
        $request['map']['name'] = $this->setName($request);
        
        return $request;
    }

    private function setPath(array $request): string
    {
        return $this->append(implode('.', array_map(
            fn ($x) => ConvertCase::_($x, Settings::resources('blade.convention') ?? 'kebab'),
            explode('.', str_replace(DIRECTORY_SEPARATOR, '.', $request['map']['subs']))
        )));
    }

    private function setName(array $request): string
    {
        return $this->append(ConvertCase::_($request['attr']['name'], Settings::resources('blade.convention') ?? 'kebab'));
    }

    private function append($str)
    {
        return Text::append($str, '.');
    }
}