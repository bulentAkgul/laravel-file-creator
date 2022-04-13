<?php

namespace Bakgul\FileCreator\Services\RequestServices;

use Bakgul\FileCreator\Services\RegistrationService;

class SrcRegistrationRequestService extends RegistrationService
{
    protected function setCodeLine(array $map): string
    {
        return "use {$map['namespace']}\\{$map['class']};";
    }

    protected function setCodeBlock(array $map, bool $isArray = false): string
    {
        return "{$map['class']}::class" . ($isArray ? ' => []' : '');
    }
}
