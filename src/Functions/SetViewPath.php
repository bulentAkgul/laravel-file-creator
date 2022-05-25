<?php

namespace Bakgul\FileCreator\Functions;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;

class SetViewPath
{
    public static function _($request)
    {
        ray($request);
        $registrar = match (true) {
            Settings::standalone('laravel') => '',
            Settings::standalone('package') => Settings::identity('registrar'),
            default => $request['attr']['package']
        };

        return Text::prepend($registrar, '::')
            . str_replace(Settings::seperators('folder'), '.', implode('.', array_filter([
                Settings::folders('apps'),
                $request['attr']['app_folder'],
                Settings::folders('view'),
                ...array_filter(explode(':', $request['attr']['views'])),
            ])))
            . '.';
    }
}
