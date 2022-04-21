<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\EnumRequestService;
use Bakgul\Kernel\Functions\CreateFile;

class EnumFileService extends SrcFilesService
{
    public function __invoke(array $request): void
    {
        CreateFile::_((new EnumRequestService)($request));
    }
}
