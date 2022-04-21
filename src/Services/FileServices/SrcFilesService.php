<?php

namespace Bakgul\FileCreator\Services\FileServices;

use Bakgul\FileCreator\Services\FileService;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;
use Bakgul\Kernel\Functions\CallClass;
use Bakgul\Kernel\Functions\CreateFile;

class SrcFilesService extends FileService
{
    public function create(array $request): void
    {
        $request = (new SrcRequestService)->handle($request);

        CallClass::_($request, 'src', __NAMESPACE__) ?: CreateFile::_($request);
    }
}
