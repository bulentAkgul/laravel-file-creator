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
            'start' => [$this->setStartSearch(), 0],
            'end' => ['])', 0],
            'repeat' => 2,
            'isSortable' => true,
            'bracket' => '[]',
            'jump' => "Route::{$this->routeMethod()}(["
        ];
    }

    private function setStartSearch()
    {
        return $this->isAPI()
            ? "Route::prefix('api/{$this->routeGroup()}')"
            : "Route::prefix('" . $this->routeGroup() . "')";
    }

    private function routeGroup()
    {
        return $this->request['attr']['route_group'];
    }

    private function routeMethod()
    {
        return $this->isAPI() ? 'apiResources' : 'resources'; 
    }

    private function isAPI(): bool
    {
        return str_contains($this->request['attr']['variation'], 'api');
    }
}
