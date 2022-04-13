<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;

class ProviderRequestService extends SrcRequestService
{
    public function __invoke(array $request, string $name): array
    {
        return [
            'attr' => array_merge($request['attr'], [
                'path' => Text::changeTail($request['attr']['path'], 'Providers'),
                'stub' => Text::changeTail($request['attr']['stub'], 'provider.event.stub'),
                'file' => "{$name}ServiceProvider.php",
                'force' => false,
            ]),
            'map' => array_merge($request['map'], [
                'namespace' => Text::changeTail($request['map']['namespace'], 'Providers', '\\'),
                'container' => 'Providers'
            ])
        ];
    }
}