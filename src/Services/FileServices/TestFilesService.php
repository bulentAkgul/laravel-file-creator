<?php

namespace Bakgul\FileCreator\Services\FileServices;

use Bakgul\FileCreator\Services\FileService;
use Bakgul\FileCreator\Services\RequestServices\TestRequestService;

class TestFilesService extends FileService
{
    public function create(array $request): void
    {
        $request = (new TestRequestService)->handle($request);

        $this->isClassExist($request['attr']['type'], 'Test')
            ? $this->callClass($request, 'Test')
            : $this->createFile($request);
    }
}
