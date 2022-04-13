<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationServices\EventFilesRegistrationService;

class EventFileService extends SrcFilesService
{
    public function __invoke(array $request)
    {
        $this->createFile($request);

        (new EventFilesRegistrationService)($request);
    }
}
