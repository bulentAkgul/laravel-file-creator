<?php

namespace Bakgul\FileCreator\Services;

use Bakgul\Kernel\Concerns\HasRequest;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\FileContent\Functions\MakeFile;
use Bakgul\FileContent\Tasks\CompleteFolders;
use Bakgul\FileCreator\FileCreator;
use Bakgul\FileCreator\Services\FileServices\DatabaseFilesService;
use Bakgul\FileCreator\Services\FileServices\SrcFilesService;
use Bakgul\FileCreator\Services\FileServices\TestFilesService;
use Bakgul\Kernel\Services\NotExpectedTypeService;
use Illuminate\Support\Str;

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

    public function createFile($request)
    {
        if ($this->isFileNotCreatable($request['attr'])) return;

        CompleteFolders::_($request['attr']['path']);

        MakeFile::_($request);
    }

    protected function isFileNotCreatable(array $attr): bool
    {
        return !$attr['force'] && file_exists(Path::glue([$attr['path'], $attr['file']]));
    }

    protected function isClassExist(string $type, string $family)
    {
        return Folder::contains(
            Path::glue([__DIR__, 'FileServices', "{$family}FilesServices"]),
            ucfirst($type) . 'FileService.php'
        );
    }

    protected function callClass(array $request, string $family)
    {
        (new ($this->class($request['attr']['type'], $family)))($request);
    }

    private function class(string $type, string $family): string
    {
        return get_class() . "s\\{$family}FilesServices\\" . ucfirst(Str::singular($type)) . 'FileService';
    }
}
