<?php

namespace Bakgul\FileCreator\Services\FileServices\SrcFilesServices;

use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\RegistrationServices\ProviderFilesRegistrationService;
use Bakgul\Kernel\Functions\CreateFile;

class ProviderFileService extends SrcFilesService
{
    public function create(array $request): void
    {
        CreateFile::_($request);

        (new ProviderFilesRegistrationService)($request);
    }
}
