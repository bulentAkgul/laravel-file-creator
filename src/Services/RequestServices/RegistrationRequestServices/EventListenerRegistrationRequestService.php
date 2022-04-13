<?php

namespace Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileCreator\Services\RequestServices\SrcRegistrationRequestService;

class EventListenerRegistrationRequestService extends SrcRegistrationRequestService
{
    public function __invoke(array $request): array
    {
        return [
            'attr' => array_merge($request['attr'], [
                'target_file' => $this->target($request['attr']['path']) 
            ]),
            'map' => array_merge($request['map'], [
                'imports' => $this->setCodeLine($request['map']),
                'block' => $this->setCodeBlock($request['map'], true),
            ]),
        ];
    }

    protected function target(string $path): string
    {
        return Text::changeTail($path, Path::glue(['Providers', 'EventServiceProvider.php']));
    }
}