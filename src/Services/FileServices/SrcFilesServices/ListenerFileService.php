<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationServices\ListenerFilesRegistrationService;

class ListenerFileService extends SrcFilesService
{
    public function __invoke(array $request)
    {
        $this->createFile($request);

        (new ListenerFilesRegistrationService)($request);
    }
}
