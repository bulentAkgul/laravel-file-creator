<?php

namespace Bakgul\FileCreator\Services\RegistrationServices;

use Bakgul\FileCreator\Services\RegistrationService;
use Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices\ControllerRegistrationRequestService;

class ControllerFilesRegistrationService extends RegistrationService
{
    public function __invoke(array $request): void
    {
        $this->setRequest((new ControllerRegistrationRequestService)($request));

        $this->register($this->lineSpecs(), $this->blockSpecs());
    }

    private function lineSpecs()
    {
        return [
            'start' => ["use", 0],
            'end' => ["Route::", 0],
            'findEndBy' => 'use'
        ];
    }

    private function blockSpecs(): array
    {
        return [
            'start' => ["Route::{$this->routeMethod()}([", 0],
            'end' => ['])', 0],
            'repeat' => str_contains($this->routeMethod(), 'api') ? 1 : 0,
            'isSortable' => true,
            'bracket' => '[]'
        ];
    }

    private function routeMethod()
    {
        return str_contains($this->request['attr']['variation'], 'api') ? 'apiResources' : 'resources'; 
    }
}
