<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;

class EnumRequestService extends SrcRequestService
{
    public function __invoke(array $request): array
    {
        return [
            'attr' => array_merge($request['attr'], []),
            'map' => array_merge($request['map'], [
                'cases' => $this->setCases($request['attr']['variation']),
                'type_hint' => $this->setType($request['attr']['variation']),
            ]),
        ];
    }
    
    private function setCases(string $variation): string
    {
        if (!$variation) return '';

        $type = $this->getType($variation);
        
        return implode(PHP_EOL, array_map(fn ($x) => $this->setCase($x, $type), $this->getCases($variation, $type)));
    }

    private function setType(string $variation): string
    {
        $type = $this->getType($variation);

        return $type ? ": {$type}" : $type;
    }

    private function getCases(string $variation, string $type): array
    {
        return explode(
            Settings::seperators('addition'),
            str_replace([$type, Settings::seperators('chunk')], '', $variation)
        );
    }

    private function setCase(string $case, string $type)
    {
        return "    case {$case} = " . $this->setInitialValue($type) . ";"; 
    }

    private function setInitialValue(string $type)
    {
        return match($type) {
            'int' => 0,
            'bool' => 'true',
            'array' => '[]',
            default => "''",
        };
    }
    
    private function getType(string $variation): string
    {
        return strtolower(explode(Settings::seperators('chunk'), $variation)[0]);
    }
}