<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\ServiceRequestService;

class ServiceFileService extends SrcFilesService
{
    public function __invoke(array $request)
    {
        $this->createFile((new ServiceRequestService)($request));
    }
}
