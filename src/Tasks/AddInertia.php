<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\FileContent\Helpers\Content;
use Bakgul\FileContent\Tasks\WriteToFile;
use Bakgul\Kernel\Functions\ExtractNames;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tasks\ConvertCase;
use Illuminate\Support\Str;

class AddInertia
{
    private static $method;

    public static function controller(array $request): void
    {
        if ($request['attr']['router'] != 'inertia') return;

        $file = Path::glue([$request['attr']['path'], $request['attr']['file']]);

        $content = Content::read($file, purify: false);

        foreach ($content as $i => $line) {
            if (self::isNotRenderable($i, $content)) continue;

            $content[$i] = self::modifyLine($line, $request);
        }

        WriteToFile::handle($content, $file);
    }

    private static function isNotRenderable($i, $content)
    {
        if ($i < 3) return true;

        self::setMethod($content[$i - 2]);

        return !in_array(self::$method, Settings::resources('inertia.renderables'));
    }

    private static function setMethod($line)
    {
        self::$method = str_contains($line, 'function')
            ? trim(Str::between($line, 'function', '('))
            : '';
    }

    private static function modifyLine($line, $request)
    {
        return trim(str_replace(['return ', ';'], [self::line($request), ''], $line), "\r\n;") . ');' . PHP_EOL;
    }

    private static function line(array $request)
    {
        return "return Inertia::render('" . implode('/', array_filter([
            $n = self::getName($request['attr']),
            ...self::subs($request['attr']),
            self::setFile($request['attr']['app_type'], $n)
        ])) . "', ";
    }

    private static function subs(array $attr): array
    {
        $convention = Settings::resources("{$attr['app_type']}.convention") ?? 'pascal';

        return array_map(fn ($x) => ConvertCase::_($x, $convention), $attr['subs']);
    }

    private static function getName(array $attr): string
    {
        foreach (ExtractNames::_($attr['command']['name']) as $name) {
            if (self::isTheName($attr['name'], $name)) return ucfirst($name);
        }
    }

    private static function isTheName(string $name, string $commandName): bool
    {
        return Str::plural($name) == $commandName || Str::singular($name) == $commandName;
    }

    private static function setFile(string $app, string $name): string
    {
        return implode('', array_map(
            fn ($x) => ConvertCase::_($x, Settings::resources("{$app}.convention") ?? 'pascal'),
            Settings::main('tasks_as_sections')
                ? [$name, self::$method]
                : [self::$method, $name]
        ));
    }
}
