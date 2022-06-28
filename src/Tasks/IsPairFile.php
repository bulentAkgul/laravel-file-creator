<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\Kernel\Helpers\Arry;

class IsPairFile
{
    public static function _(string $type, array $queue, string $of = ''): bool
    {
        return self::isPair($type, $queue) && self::isPairOf($of, $queue);
    }

    private static function isPair(string $type, array $queue): bool
    {
        return empty(self::filter($type, $queue));
    }

    private static function isPairOf(string $type, array $queue): bool
    {
        return $type == '' || !self::isPair($type, $queue);
    }

    private static function filter($type, $queue): array
    {
        return Arry::get(array_filter($queue, function($x) use ($type) {
            return $x['type'] == $type && $x['status'] == 'main' && $x['order'] == 'main';
        }), 0) ?? [];
    }
}