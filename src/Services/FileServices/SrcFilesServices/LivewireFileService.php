<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\LivewireRequestService;
use Bakgul\FileContent\Functions\CreateFile;

class LivewireFileService extends SrcFilesService
{
    public function create(array $request): void
    {
        CreateFile::_((new LivewireRequestService)($request));
    }
}
