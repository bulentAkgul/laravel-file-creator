<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\ComponentRequestService;
use Bakgul\Kernel\Functions\CreateFile;

class ComponentFileService extends SrcFilesService
{
    public function create(array $request): void
    {
        CreateFile::_((new ComponentRequestService)($request));
    }
}
