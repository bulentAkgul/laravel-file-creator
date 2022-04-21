<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\LivewireRequestService;
use Bakgul\Kernel\Functions\CreateFile;

class LivewireFileService extends SrcFilesService
{
    public function __invoke(array $request): void
    {
        CreateFile::_((new LivewireRequestService)($request));
    }
}
