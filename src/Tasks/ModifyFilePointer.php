<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;

class ModifyFilePointer
{
    public static function path(array $request): string
    {
        return self::isModifiable($request['attr']) ? str_replace(
            Path::glue(['', 'app', $request['map']['container']]),
            Path::glue(['', 'app', 'Http', $request['map']['container']]),
            $request['attr']['path']
        ) : $request['attr']['path'];
    }

    public static function namespace(array $request)
    {
        return self::isModifiable($request['attr']) ? str_replace(
            "\\{$request['map']['container']}",
            "\Http\\{$request['map']['container']}",
            $request['map']['namespace']
        ) : $request['map']['namespace'];
    }

    private static function isModifiable($attr)
    {
        return (Settings::standalone('laravel')
            || !Settings::standalone() && !$attr['package'])
            && in_array($attr['type'], ['controller', 'request', 'resource', 'middleware']);
    }
}
