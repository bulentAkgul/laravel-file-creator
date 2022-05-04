<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\Kernel\Helpers\Package;
use Bakgul\Kernel\Helpers\Settings;

class ModifyFilePointer
{
    public static function path(array $request): string
    {
        return self::isModifiable($request['attr'])
            ? self::modify($request['attr']['path'], $request['map']['wrapper'])
            : $request['attr']['path'];
    }

    public static function namespace(array $request)
    {
        return self::isModifiable($request['attr'])
            ? self::modify($request['map']['namespace'], $request['map']['wrapper'], '\\')
            : $request['map']['namespace'];
    }

    private static function modify($pointer, $remove, $glue = DIRECTORY_SEPARATOR)
    {
        return str_replace("{$glue}{$remove}{$glue}", $glue, $pointer);
    }

    private static function isModifiable($attr)
    {
        return match(true) {
            !str_contains($attr['path_schema'], 'wrapper') => false,
            Settings::standalone('laravel') => false,
            Settings::standalone('package') => Settings::main('expand_http_in_packages'),
            default => Settings::main('expand_http_in_packages') && Package::root($attr['package'])
        };
    }
}
