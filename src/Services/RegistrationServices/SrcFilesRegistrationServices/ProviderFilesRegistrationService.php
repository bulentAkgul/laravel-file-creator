<?php

namespace Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationServices;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\FileCreator\Services\RegistrationServices\SrcFilesRegistrationService;

class ProviderFilesRegistrationService extends SrcFilesRegistrationService
{
    public function __invoke(array $request): void
    {
        $this->setRequest($this->modifyRequest($request));

        $this->getTargetFileContent();

        $this->register($this->overwriteLineSpecs($request['attr']['package']), $this->setBlockSpecs());
    }

    private function modifyRequest(array $request): array
    {
        return [
            'attr' => array_merge($request['attr'], [
                'target_file' => $this->setTargetFile($request['attr'])
            ]),
            'map' => array_merge($request['map'], [
                'imports' => $this->setCodeLine($request['map']),
                'block' => $this->setCodeBlock($request['map']),
            ]),
        ];
    }

    private function setTargetFile(array $attr): string
    {
        return Text::changeTail($attr['path'], $this->setProvider($attr['package']) . '.php');
    }

    private function setProvider(string $package): string
    {
        return ConvertCase::pascal($package) . 'ServiceProvider';
    }

    protected function setCodeLine(array $map): string
    {
        return 'use ' . Text::changeTail($map['namespace'], "Providers\\EventServiceProvider;", '\\');
    }

    protected function setCodeBlock(array $map, bool $isArray = false): string
    {
        return '$this->app->register(EventServiceProvider::class)';
    }


    private function setBlockSpecs(): array
    {
        return [
            'start' => ['public function register()', 1],
            'end' => ['}', 0],
            'isStrict' => true,
            'part' => '{',
            'repeat' => 1,
            'isSortable' => false,
            'eol' => ';'
        ];
    }

    private function overwriteLineSpecs(string $package)
    {
        return [
            'end' => ['class ' . $this->setProvider($package), 0],
            'findEndBy' => 'use'
        ];
    }
}