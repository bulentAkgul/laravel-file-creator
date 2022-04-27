<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RegistrationServices\ControllerFilesRegistrationService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\ControllerRequestService;
use Bakgul\FileCreator\Tasks\AddInertia;
use Bakgul\FileCreator\Tasks\PurifyController;
use Bakgul\Kernel\Functions\CreateFile;

class ControllerFileService extends SrcFilesService
{
    private $request;

    public function create(array $request): void
    {
        $this->request = (new ControllerRequestService($request))();

        CreateFile::_($this->request);

        PurifyController::_($this->request);

        AddInertia::controller($this->request);
        
        (new ControllerFilesRegistrationService)($this->request);
    }
}
