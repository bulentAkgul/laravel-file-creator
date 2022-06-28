<?php

namespace Bakgul\FileCreator\Services\RequestServices\FileRequestServices;

use Bakgul\FileCreator\Services\RequestServices\SrcRequestService;

class ClassRequestService extends SrcRequestService
{
    public function __invoke(array $request): array
    {
        $request['map']['method'] = $this->method($request);

        return $request;
    }

    private function method(array $request)
    {
        return match ($request['attr']['variation']) {
            'static' => $this->staticMethod(),
            'invokable' => $this->invokableMethod(),
            default => '    //'
        };
    }
    
    private function staticMethod(): string
    {
        return $this->makeMethod('public static function _()');
    }
    
    private function invokableMethod(): string
    {
        return $this->makeMethod('public function __invoke()');
    }

    private function makeMethod($declaration)
    {
        $ind = str_repeat(' ', 4);

        return implode(PHP_EOL, [
            $ind . $declaration, $ind . '{', str_repeat($ind, 2) . '//', $ind . '}'
        ]);
    }
}