<?php

namespace Bakgul\FileCreator\Functions;

use Bakgul\Kernel\Tasks\ConvertCase;

class SetProvider
{
    public static function _(string $package): string
    {
        return ConvertCase::pascal($package) . 'ServiceProvider';
    }
}