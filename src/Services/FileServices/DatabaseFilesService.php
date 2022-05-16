<?php

namespace Bakgul\FileCreator\Services\FileServices;

use Bakgul\FileCreator\Services\FileService;
use Bakgul\FileCreator\Services\RequestServices\DatabaseRequestService;
use Bakgul\Kernel\Functions\CallClass;
use Bakgul\FileContent\Functions\CreateFile;

class DatabaseFilesService extends FileService
{
    public function create(array $request): void
    {
        $request = (new DatabaseRequestService)->handle($request);

        CallClass::_($request, 'database', __NAMESPACE__) ?: CreateFile::_($request);
    }
}
