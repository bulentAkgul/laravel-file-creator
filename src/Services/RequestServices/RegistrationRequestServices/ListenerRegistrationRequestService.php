<?php

namespace Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices;

use Bakgul\FileCreator\Services\RequestServices\SrcRegistrationRequestService;

class ListenerRegistrationRequestService extends SrcRegistrationRequestService
{
    public function __invoke(array $request): array
    {
        return [
            'attr' => array_merge($request['attr'], []),
            'map' => array_merge($request['map'], [
                'imports' => $this->setCodeLine($request['map']),
                'block' => $this->setCodeBlock($request['map']),
            ]),
        ];
    }
}