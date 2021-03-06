<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\EnumRequestService;
use Bakgul\FileContent\Functions\CreateFile;

class EnumFileService extends SrcFilesService
{
    public function create(array $request): void
    {
        CreateFile::_((new EnumRequestService)($request));
    }
}
