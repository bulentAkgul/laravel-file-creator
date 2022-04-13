<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\RequestRequestService;

class RequestFileService extends SrcFilesService
{
    public function __invoke(array $request): void
    {
        $this->createFile((new RequestRequestService)($request));
    }
}
