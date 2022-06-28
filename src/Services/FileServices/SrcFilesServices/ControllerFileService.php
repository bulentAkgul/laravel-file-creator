<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RegistrationServices\ControllerFilesRegistrationService;
use Bakgul\FileCreator\Services\RequestServices\FileRequestServices\ControllerRequestService;
use Bakgul\FileContent\Functions\CreateFile;
use Bakgul\FileCreator\Tasks\AddAuthorizationMethods;
use Bakgul\FileCreator\Tasks\AddInertia;
use Bakgul\FileCreator\Tasks\AddViewReturn;
use Bakgul\FileCreator\Tasks\PurifyController;

class ControllerFileService extends SrcFilesService
{
    private $request;

    public function create(array $request): void
    {
        $this->request = (new ControllerRequestService($request))();

        CreateFile::_($this->request);

        PurifyController::_($this->request);

        AddInertia::controller($this->request);

        AddViewReturn::_($this->request);

        AddAuthorizationMethods::_($this->request);

        (new ControllerFilesRegistrationService)($this->request);
    }
}
