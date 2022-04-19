<?php

namespace Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationServices;

use Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationService;
use Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices\ControllerRegistrationRequestService;
use Bakgul\FileCreator\Tasks\AddInertia;

class ControllerFilesRegistrationService extends SrcFilesRegistrationService
{
    public function __invoke(array $request): void
    {
        $this->setRequest((new ControllerRegistrationRequestService)($request));

        $this->getTargetFileContent();

        $this->register($this->overwriteLineSpecs(), $this->setBlockSpecs());
    }

    private function overwriteLineSpecs()
    {
        return [
            'start' => ["use", 0],
            'end' => ["Route::", 0],
            'findEndBy' => 'use'
        ];
    }

    private function routeMethod()
    {
        return str_contains($this->request['attr']['variation'], 'api') ? 'apiResources' : 'resources'; 
    }

    private function setBlockSpecs(): array
    {
        return [
            'start' => ["Route::{$this->routeMethod()}([", 0],
            'end' => ['])', 0],
            'isStrict' => true,
            'part' => '',
            'repeat' => str_contains($this->routeMethod(), 'api') ? 1 : 0,
            'isSortable' => true,
            'bracket' => '[]'
        ];
    }
}
