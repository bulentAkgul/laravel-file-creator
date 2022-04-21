<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Tasks\GenerateNamespace;

class ExtendFileMap
{
    public static function _(array $attr): array
    {
        return [
            'root_namespace' => GenerateNamespace::_($attr),
            'container' => self::setContainer($attr['type']),
            'app' => self::setApp($attr),
            'table' => ''
        ];
    }

    private static function setApp(array $attr)
    {
        return str_contains($attr['path_schema'], '{{ app }}') ? self::setValue($attr['app_folder']) : '';
    }

    private static function setContainer(string $key): string
    {
        return self::setValue(Folder::get($key));
    }

    private static function setValue(string $value, string $case = 'pascal'): string
    {
        return Arry::get(Path::make($value, $case), 0) ?? '';
    }
}
