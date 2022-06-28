<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\ClassRequestService;
use Bakgul\FileContent\Functions\CreateFile;

class ClassFileService extends SrcFilesService
{
    public function create(array $request): void
    {
        CreateFile::_((new ClassRequestService)($request));
    }
}
