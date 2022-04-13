<?php

namespace Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationServices;

use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationService;
use Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices\EventListenerRegistrationRequestService;

class ListenerFilesRegistrationService extends SrcFilesRegistrationService
{
    public function __invoke(array $request): void
    {
        $this->setRequest((new EventListenerRegistrationRequestService)($request));

        $this->getTargetFileContent();

        if ($this->isNotRegisterable()) return;

        $this->register(
            $this->overwriteLineSpecs(),
            $this->setBlockSpecs($request['attr']['parent']['name'] ?: $request['attr']['name'])
        );
    }

    private function setBlockSpecs(string $parent): array
    {
        return [
            'start' => [$s = $this->setStart($parent), 0],
            'end' => [']', 0],
            'isStrict' => true,
            'part' => $s,
            'repeat' => 2,
            'isSortable' => true,
            'bracket' => '[]'
        ];
    }

    private function setStart(string $parent)
    {
        return Convention::class($parent) . '::class';
    }

    private function overwriteLineSpecs()
    {
        return [
            'end' => ['class EventServiceProvider', 0],
            'findEndBy' => 'use'
        ];
    }
}
