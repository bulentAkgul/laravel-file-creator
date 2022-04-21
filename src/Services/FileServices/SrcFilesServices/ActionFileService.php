<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\ActionRequestService;
use Bakgul\Kernel\Functions\CreateFile;

class ActionFileService extends SrcFilesService
{
    public function __invoke(array $request): void
    {
        CreateFile::_((new ActionRequestService)($request));
    }
}
