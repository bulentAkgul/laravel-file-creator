<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\ServiceRequestService;
use Bakgul\Kernel\Functions\CreateFile;

class ServiceFileService extends SrcFilesService
{
    public function create(array $request): void
    {
        CreateFile::_((new ServiceRequestService)($request));
    }
}
