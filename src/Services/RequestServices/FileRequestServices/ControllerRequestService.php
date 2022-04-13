<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;
use Bakgul\FileCreator\Tasks\AddInertia;
use Bakgul\FileCreator\Tasks\ModifyFilePointer;

class ControllerRequestService extends SrcRequestService
{
    public function __construct(private array $request) {}

    public function __invoke(): array
    {
        return [
            'attr' => array_merge($this->request['attr'], [
                'stub' => $this->modifyStub(),
                'path' => ModifyFilePointer::path($this->request)
            ]),
            'map' => array_merge($this->request['map'], [
                'namespace' => ModifyFilePointer::namespace($this->request),
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
                'package' => $this->request['attr']['package']
            ],
            'map' => [
                'container' => Convention::namespace($type['type']),
                'namespace' => $line,
            ]
        ]);
    }

    private function generateNamespace($type)
    {
        return str_replace([DIRECTORY_SEPARATOR, '\\\\'], '\\', Text::replaceByMap(
            $this->modifyMap($type),
            'use {{ root_namespace }}\{{ types }}\{{ pair_name }}{{ types }}\{{ subs }}\{{ task }}{{ pair_name }}{{ type }};'
        ));
    }

    private function modifyMap(array $type): array
    {
        return array_merge($this->request['map'], [
            'types' => Convention::namespace($type['type']),
            'pair_name' => Convention::class($type['name']),
            'task' => ucfirst($type['task']),
            'type' => ucfirst($type['type']),
        ]);
    }
}
