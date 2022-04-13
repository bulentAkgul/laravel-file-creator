<?php

namespace Bakgul\FileCreator\Services\FileServices;

use Bakgul\FileCreator\Services\FileService;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;

class SrcFilesService extends FileService
{
    public function create(array $request): void
    {
        $request = (new SrcRequestService)->handle($request);

        $this->isClassExist($request['attr']['type'], 'Src')
            ? $this->callClass($request, 'Src')
            : $this->createFile($request);
    }
}
