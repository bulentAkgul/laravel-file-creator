<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;
use Bakgul\FileCreator\Tasks\ModifyFilePointer;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;

class ControllerRequestService extends SrcRequestService
{
    public function __construct(private array $request) {}

    public function __invoke(): array
    {
        return [
            'attr' => array_merge($this->request['attr'], [
                'stub' => $this->modifyStub(),
            ]),
            'map' => array_merge($this->request['map'], [
                'uses' => $this->setUseLines('request'),
            ]),
        ];
    }

    private function modifyStub()
    {
        return str_replace('-api', '', $this->request['attr']['stub']);
    }

    private function setUseLines(): string
    {
        if ($this->request['attr']['variation'] == 'invokable') return '';
        
        $uses = [$this->setInertia()];

        foreach (['request', 'service'] as $type) {
            if (!in_array($type, $this->request['attr']['pairs'])) continue;

            $uses[] = implode(PHP_EOL, array_map(
                fn ($x) => $this->makeLine($x),
                $this->getUseFiles($type)
            ));
        }

        return implode(PHP_EOL, array_filter($uses)) . PHP_EOL;
    }

    private function setInertia()
    {
        return $this->request['attr']['router'] == 'inertia' ? 'use Inertia\Inertia;' : '';
    }

    private function getUseFiles(string $type): array
    {
        return array_filter(
            $this->request['attr']['queue'],
            fn ($x) => $this->request['attr']['name'] == $x['name'] && $type == $x['type'] && $this->request['attr']['package'] == $x['package']
        );
    }

    private function makeLine(array $type): string
    {
        return $this->modifyLine($this->generateNamespace($type), $type);
    }

    private function modifyLine($line, $type)
    {
        return ModifyFilePointer::namespace([
            'attr' => [
                'type' => $type['type'],
                'package' => $this->request['attr']['package'],
                'path_schema' => Settings::files("{$type['type']}.path_schema")
            ],
            'map' => [
                'namespace' => $line,
                'wrapper' => $this->request['map']['wrapper']
            ]
        ]);
    }

    private function generateNamespace($type)
    {
        return 'use ' . str_replace(['\\\\', '/'], '\\', Text::replaceByMap(
            $this->modifyMap($type), $this->schema($type['type'])
        )) . ';';
    }

    private function modifyMap(array $type): array
    {
        return array_merge($this->request['map'], [
            'container' => $c = Convention::namespace($type['type']),
            'name' => $n = Convention::class($type['name']),
            'folder' => $n . $c,
            'task' => Convention::class($type['task']),
            'suffix' => Convention::class($type['type']),
        ]);
    }

    private function schema(string $type)
    {
        return Path::glue([
            '{{ root_namespace }}',
            str_replace('}{', '}\{', Settings::files("{$type}.path_schema")),
            Settings::files("{$type}.name_schema")
        ], '\\');
    }
}
