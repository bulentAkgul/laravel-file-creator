<?php

namespace Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationServices;

use Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationService;
use Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices\EventListenerRegistrationRequestService;

class EventFilesRegistrationService extends SrcFilesRegistrationService
{
    public function __invoke(array $request): void
    {
        $this->setRequest((new EventListenerRegistrationRequestService)($request));

        $this->getTargetFileContent();

        $this->register($this->overwriteLineSpecs(), $this->setBlockSpecs());
    }

    private function overwriteLineSpecs()
    {
        return [
            'end' => ['class EventServiceProvider', 0],
            'findEndBy' => 'use'
        ];
    }

    private function setBlockSpecs(): array
    {
        return [
            'start' => ['protected $listen', 0],
            'end' => [']', 0],
            'isStrict' => true,
            'part' => 'protected',
            'repeat' => 1,
            'isSortable' => false,
            'bracket' => '[]'
        ];
    }
}
