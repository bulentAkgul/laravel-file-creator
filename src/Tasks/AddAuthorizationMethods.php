<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\FileContent\Helpers\Content;
use Bakgul\FileContent\Tasks\WriteToFile;
use Bakgul\FileCreator\Functions\GetMethodName;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Text;
use Illuminate\Support\Arr;

class AddAuthorizationMethods
{
    private static $content;
    private static $file;
    private static $map;
    private static $methods = [
        'index' => ['viewAny', '{{ name }}::class'],
        'show' => ['view', '${{ var }}'],
        'store' => ['create', '{{ name }}::class'],
        'create' => ['create', '{{ name }}::class'],
        'edit' => ['edit', '${{ var }}'],
        'update' => ['edit', '${{ var }}'],
        'destroy' => ['delete', '${{ var }}']
    ];

    public static function _(array $request): void
    {
        if (self::hasNoPolicy($request['attr'])) return;

        self::setVariables($request);

        self::addMethods();
    }

    private static function hasNoPolicy(array $attr): bool
    {
        return empty(array_filter($attr['queue'], fn ($x) => $x['type'] == 'policy' && $x['name'] == $attr['name']));
    }

    private static function setVariables($request)
    {
        self::$map = $request['map'];
        self::$file = Path::glue([$request['attr']['path'], $request['attr']['file']]);
        self::$content = Content::read(self::$file);
    }

    private static function addMethods()
    {
        foreach (self::$content as $i => $line) {
            if (self::isNotMethodDeclaration($line)) continue;

            self::$content[$i + 2] = self::extendCode($line, $i + 2);
        }

        WriteToFile::_(self::setContent(), self::$file);
    }

    private static function isNotMethodDeclaration(string $line): bool
    {
        return Text::hasNot($line, 'public function');
    }

    private static function extendCode($line, $index)
    {
        return [self::authorize(GetMethodName::_($line)), '', self::$content[$index]];
    }

    private static function authorize(string $method)
    {
        return str_repeat(' ', 8)
            . '$this->authorize('
            . self::method($method)
            . ', '
            . self::argument($method)
            . ');';
    }

    private static function method($method)
    {
        return Text::wrap(self::$methods[$method][0], 'sq');
    }

    private static function argument($method)
    {
        return Text::replaceByMap(self::$map, self::$methods[$method][1], glue: '');
    }

    private static function setContent()
    {
        return array_map(fn ($x) => $x . PHP_EOL, Arr::flatten(self::$content));
    }
}
