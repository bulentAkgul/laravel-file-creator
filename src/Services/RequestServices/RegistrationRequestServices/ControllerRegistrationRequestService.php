<?php

namespace Bakgul\FileCreator\Services\RequestServices\RegistrationRequestServices;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Pluralizer;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\FileCreator\Services\RequestServices\SrcRegistrationRequestService;

class ControllerRegistrationRequestService extends SrcRegistrationRequestService
{
    public function __invoke(array $request): array
    {
        return [
            'attr' => array_merge($request['attr'], [
                'target_file' => $this->target($request['attr']) 
            ]),
            'map' => array_merge($request['map'], [
                'imports' => $this->setCodeLine($request['map']),
                'block' => $this->setRoute($request['map']),
            ]),
        ];
    }

    protected function target(array $attr): string
    {
        return Path::glue([Path::head($attr['package'], 'routes'), $this->file($attr)]);
    }

    private function file($attr)
    {
        return ($this->isWeb($attr) ? 'web' : 'api') . '.php';  
    }

    private function isWeb($attr)
    {
        return $attr['router'] == 'inertia' || !str_contains($attr['variation'], 'api');
    }

    private function setRoute(array $map)
    {
        return "'{$this->slug($map['name'])}' => {$map['class']}::class";
    }

    private function slug(string $name): string
    {
        return Pluralizer::make(ConvertCase::kebab($name), isSingular: false);
    }
}