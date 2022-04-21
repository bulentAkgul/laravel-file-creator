<?php

namespace Bakgul\FileCreator\Services;

use Bakgul\Kernel\Concerns\HasRequest;
use Bakgul\FileCreator\FileCreator;
use Bakgul\FileCreator\Services\FileServices\DatabaseFilesService;
use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\FileServices\TestFilesService;

class FileService extends FileCreator
{
    use HasRequest;

    public function create(array $request): void
    {
        $request = (new RequestService)->handle($request);

        $this->service($request['attr']['family'])->create($request);
    }

    private function service(string $family): object
    {
        return match ($family) {
            'tests' => new TestFilesService,
            'database' => new DatabaseFilesService,
            default => new SrcFilesService
        };
    }
}
