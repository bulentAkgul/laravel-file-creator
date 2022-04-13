<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\FileContent\Helpers\Content;
use Bakgul\FileContent\Tasks\WriteToFile;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Illuminate\Support\Str;

class AddInertia
{
    private static $method;

    public static function controller(array $request)
    {
        $file = Path::glue([$request['attr']['path'], $request['attr']['file']]);

        $content = Content::read($file, purify: false);

        foreach ($content as $i => $line) {
            if (self::isNotRenderable($i, $content)) continue;

            $content[$i] = self::modifyLine($line, $request['map']['name']);
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

    private static function modifyLine($line, $name)
    {
        return trim(str_replace(['return ', ';'], [self::line($name), ''], $line), "\r\n;") . ');' . PHP_EOL;
    }

    private static function line($name)
    {
        return "return Inertia::render('{$name}/" . ucfirst(self::$method) . "', ";
    }
}
