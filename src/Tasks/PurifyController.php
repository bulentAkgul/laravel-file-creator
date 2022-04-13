<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\Kernel\Helpers\Isolation;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileContent\Helpers\Content;
use Bakgul\FileContent\Tasks\GetCodeBlock;
use Bakgul\Kernel\Helpers\Pluralizer;
use Illuminate\Support\Arr;

class PurifyController
{
    public static function _(array $request)
    {
        $tasks = self::setTasks($request['attr']);

        if (empty($tasks)) return;

        $file = Path::glue([$request['attr']['path'], $request['attr']['file']]);

        $content = Content::read($file);

        foreach ($tasks as $task) {
            [$start, $_, $end, $_] = GetCodeBlock::_($content, self::setSpecs($task));

            $content = Content::purify($content, $start, $end);
        }

        Content::write($file, self::closeClass($content));
    }

    private static function setTasks(array $attr): array
    {
        return array_diff(Settings::main('tasks.all'), self::getTasks($attr));
    }

    private static function getTasks(array $attr): array
    {
        $tasks = Isolation::tasks(array_values(array_filter(
            Arr::flatten(array_map(
                fn ($x) => Isolation::file($x),
                Isolation::chunk($attr['command']['name'])
            )),
            fn ($x) => Pluralizer::make(Isolation::name($x), $attr['name_count']) == $attr['name']
        ))[0]);

        return  in_array('all', $tasks) || empty(array_filter($tasks))
            ? Settings::main('tasks.' . (str_contains($attr['variation'], 'api') ? 'api' : 'all'))
            : $tasks;
    }

    private static function setSpecs(string $task): array
    {
        return [
            'start' => ["public function {$task}", 0],
            'end' => ['}', 1],
            'isStrict' => true,
            'part' => '',
            'repeat' => 0,
        ];
    }

    private static function closeClass(array $content): array
    {
        foreach (array_reverse($content) as $i => $line) {
            if (trim($line) == '' || !trim($line) == '}') continue;

            if (trim($line, "\n\r") != '}') {
                $content[count($content) - $i] = '}';
                break;
            }
        }

        return $content;
    }
}
