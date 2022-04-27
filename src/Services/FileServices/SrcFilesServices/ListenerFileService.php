<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RegistrationServices\ListenerFilesRegistrationService;
use Bakgul\Kernel\Functions\CreateFile;

class ListenerFileService extends SrcFilesService
{
    public function create(array $request): void
    {
        CreateFile::_($request);

        (new ListenerFilesRegistrationService)($request);
    }
}
