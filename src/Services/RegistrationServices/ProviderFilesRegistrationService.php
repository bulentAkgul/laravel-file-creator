<?php

namespace Bakgul\FileCreator\Services\RegistrationServices;

use Bakgul\FileCreator\Functions\SetProvider;
use Bakgul\FileCreator\Services\RegistrationService;
use Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices\ProviderRegistrationRequestService;

class ProviderFilesRegistrationService extends RegistrationService
{
    public function __invoke(array $request): void
    {
        $this->setRequest((new ProviderRegistrationRequestService)($request));

        $this->register($this->lineSpecs(), $this->setBlockSpecs());
    }


    private function setBlockSpecs(): array
    {
        return [
            'start' => ['public function register()', 1],
            'end' => ['}', 0],
            'part' => '{',
            'eol' => ';'
        ];
    }

    private function lineSpecs()
    {
        return [
            'end' => ['class ' . SetProvider::_($this->request['attr']['package']), 0],
            'findEndBy' => 'use'
        ];
    }
}