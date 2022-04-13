<?php

namespace Bakgul\FileCreator\Services\FileServices;

use Bakgul\FileCreator\Services\FileService;
use Bakgul\FileCreator\Services\RequestServices\DatabaseRequestService;

class DatabaseFilesService extends FileService
{
    public function create(array $request): void
    {
        $request = (new DatabaseRequestService)->handle($request);

        $this->isClassExist($request['attr']['type'], 'Database')
            ? $this->callClass($request, 'Database')
            : $this->createFile($request);
    }
}
