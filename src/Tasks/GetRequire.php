<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\Kernel\Helpers\Settings;

class GetRequire
{
    public static function _(array $specs, string $package = ''): array
    {
        $require = self::get($specs);

        return  empty($require) ? null : (self::ready($require) ?: self::make($specs, $package));
    }

    private static function get(array $specs): array
    {
        return Settings::requires(
            callback: fn ($x) => $x['name'] == $specs['name'] && $x['type'] == $specs['type']
        );
    }

    private static function ready($require): bool|array
    {
        return $require['path'] && $require['class'] ? $require : false;
    }

    private static function make(array $specs, string $package)
    {
        return [...$specs, ...self::paths($specs, $package)];
    }

    private static function paths(array $specs, string $package): array
    {
        return [$p = self::path($specs, $package), self::namespace($p)];
    }

    private static function path(array $specs, string $package): string
    {
        return '';
    }

    private static function namespace(string $path): string
    {
        return '';
    }
}