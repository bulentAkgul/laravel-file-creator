<?php

namespace Bakgul\FileCreator\Services\RegistrationServices;

use Bakgul\FileCreator\Services\RegistrationService;
use Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices\EventListenerRegistrationRequestService;

class EventFilesRegistrationService extends RegistrationService
{
    public function __invoke(array $request): void
    {
        $this->setRequest((new EventListenerRegistrationRequestService)($request));

        $this->register($this->lineSpecs(), $this->blockSpecs());
    }

    private function lineSpecs()
    {
        return [
            'end' => ['class EventServiceProvider', 0],
            'findEndBy' => 'use'
        ];
    }

    private function blockSpecs(): array
    {
        return [
            'start' => ['protected $listen', 0],
            'end' => [']', 0],
            'part' => 'protected',
            'bracket' => '[]'
        ];
    }
}
