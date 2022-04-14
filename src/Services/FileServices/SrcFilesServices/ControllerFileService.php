<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationServices\ControllerFilesRegistrationService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\ControllerRequestService;
use Bakgul\FileCreator\Tasks\AddInertia;
use Bakgul\FileCreator\Tasks\PurifyController;

class ControllerFileService extends SrcFilesService
{
    private $request;

    public function __invoke(array $request): void
    {
        $this->request = (new ControllerRequestService($request))();

        $this->createFile($this->request);

        PurifyController::_($this->request);

        AddInertia::controller($this->request);
        
        (new ControllerFilesRegistrationService)($this->request);
    }
}
