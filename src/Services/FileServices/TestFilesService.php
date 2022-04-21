<?php

namespace Bakgul\FileCreator\Services\FileServices;

use Bakgul\FileCreator\Services\FileService;
use Bakgul\FileCreator\Services\RequestServices\TestRequestService;
use Bakgul\Kernel\Functions\CallClass;
use Bakgul\Kernel\Functions\CreateFile;

class TestFilesService extends FileService
{
    public function create(array $request): void
    {
        $request = (new TestRequestService)->handle($request);

        CallClass::_($request, 'test', __NAMESPACE__) ?: CreateFile::_($request);
    }
}
