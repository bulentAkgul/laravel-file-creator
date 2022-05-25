<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\FileContent\Helpers\Content;
use Bakgul\FileContent\Tasks\WriteToFile;
use Bakgul\FileCreator\Functions\SetViewPath;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Illuminate\Support\Str;

class AddViewReturn
{
    private static $content;
    private static $method;

    public static function _(array $request): void
    {
        if (Arry::hasNot('views', $request['attr'])) return;
        
        $file = Path::glue([$request['attr']['path'], $request['attr']['file']]);

        if (self::hasNoView($request) || self::hasViewAdded($file)) return;

        self::$content = Content::read($file, purify: false);

        self::addViewPath($request);

        $methods = Settings::main('tasks_have_views');

        foreach (self::$content as $i => $line) {
            if (self::hasNoViewReturn($i, $methods)) continue;

            self::$content[$i] = self::modifyLine($line, $request);
        }

        WriteToFile::_(self::$content, $file);
    }

    private static function hasNoView($request)
    {
        return $request['attr']['router'] == 'inertia'
            || in_array($request['attr']['variation'], ['api', 'model-api', 'nested-api']);
    }

    private static function hasViewAdded($file)
    {
        foreach (file($file) as $line) {
            if (str_contains($line, 'return view')) return true;
        }

        return false;
    }

    private static function addViewPath($request)
    {
        array_splice(
            self::$content,
            Arry::containsAt('class', self::$content) + 2,
            0,
            self::setViewPath($request)
        );
    }

    private static function setViewPath($request)
    {
        return implode('', [
            str_repeat(' ', 4),
            'private string $view = ',
            Text::wrap(SetViewPath::_($request), 'sq'),
            ';'
        ]) . str_repeat(PHP_EOL, 2);
    }

    private static function hasNoViewReturn($i, $methods)
    {
        if ($i < 3) return true;

        self::setMethod(self::$content[$i - 2]);

        return !in_array(self::$method, $methods)
            || str_contains(self::$content[$i], 'return view');
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
        return 'return view(' . Text::wrap('{$this->view}' . self::view($request), 'dq') . ', ';
    }

    private static function view(array $request)
    {
        $parts = [
            self::$method,
            Isolation::name($request['attr']['command']['name'])
        ];

        return implode('-', Settings::main('tasks_as_sections') ? array_reverse($parts) : $parts);
    }
}
