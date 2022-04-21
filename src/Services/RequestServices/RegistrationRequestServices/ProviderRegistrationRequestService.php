<?php

namespace Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices;

use Bakgul\FileCreator\Functions\SetProvider;
use Bakgul\FileCreator\Services\RequestServices\SrcRegistrationRequestService;
use Bakgul\Kernel\Helpers\Text;

class ProviderRegistrationRequestService extends SrcRegistrationRequestService
{
    public function __invoke(array $request): array
    {
        return [
            'attr' => array_merge($request['attr'], [
                'target_file' => $this->setTargetFile($request['attr'])
            ]),
            'map' => array_merge($request['map'], [
                'imports' => $this->setImports($request['map']),
                'block' => $this->setBlock($request['map']),
            ]),
        ];
    }

    private function setTargetFile(array $attr): string
    {
        return Text::changeTail($attr['path'], SetProvider::_($attr['package']) . '.php');
    }

    protected function setImports(array $map): string
    {
        return 'use ' . Text::changeTail($map['namespace'], "Providers\\EventServiceProvider;", '\\');
    }

    private function setBlock(): string
    {
        return '$this->app->register(EventServiceProvider::class)';
    }
}