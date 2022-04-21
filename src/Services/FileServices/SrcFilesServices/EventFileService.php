<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RegistrationServices\EventFilesRegistrationService;
use Bakgul\Kernel\Functions\CreateFile;

class EventFileService extends SrcFilesService
{
    public function __invoke(array $request)
    {
        CreateFile::_($request);

        (new EventFilesRegistrationService)($request);
    }
}
