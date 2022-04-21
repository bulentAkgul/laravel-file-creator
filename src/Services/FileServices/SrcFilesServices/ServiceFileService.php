<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\ServiceRequestService;
use Bakgul\Kernel\Functions\CreateFile;

class ServiceFileService extends SrcFilesService
{
    public function __invoke(array $request)
    {
        CreateFile::_((new ServiceRequestService)($request));
    }
}
