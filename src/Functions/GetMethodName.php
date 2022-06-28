<?php

namespace Bakgul\FileCreator\Functions;

use Illuminate\Support\Str;

class GetMethodName
{
    public static function _(string $methodDeclaration): string
    {
        return trim(Str::between($methodDeclaration, 'function', '('));
    }
}
