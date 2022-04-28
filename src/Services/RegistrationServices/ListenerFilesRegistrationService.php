<?php

namespace Bakgul\FileCreator\Services\RegistrationServices;

use Bakgul\FileCreator\Services\RegistrationService;
use Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices\EventListenerRegistrationRequestService;
use Bakgul\Kernel\Helpers\Convention;

class ListenerFilesRegistrationService extends RegistrationService
{
    public function __invoke(array $request): void
    {
        $this->setRequest((new EventListenerRegistrationRequestService)($request));

        $this->register($this->lineSpecs(), $this->blockSpecs($this->setParent($request)));
    }

    private function setParent(array $request): string
    {
        return $request['attr']['parent']['name'] ?: $request['attr']['name'];
    }

    private function blockSpecs(string $parent): array
    {
        return [
            'start' => [$s = Convention::class($parent) . '::class', 0],
            'end' => [']', 0],
            'part' => $s,
            'repeat' => 2,
            'isSortable' => true,
            'bracket' => '[]'
        ];
    }

    private function lineSpecs()
    {
        return [
            'end' => ['class EventServiceProvider', 0],
            'findEndBy' => 'use'
        ];
    }
}
