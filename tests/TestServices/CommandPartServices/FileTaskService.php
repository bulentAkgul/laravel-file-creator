<?php

namespace Bakgul\FileCreator\Tests\TestServices\CommandPartServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\CommandServices\FileCommandService;
use Illuminate\Support\Arr;

class FileTaskService extends FileCommandService
{
    public function __invoke(array $commandCases): array
    {
        foreach ($this->getChunks($commandCases['name']['value']) as $i) {
            $commandCases['name']['value'][$i]['tasks'] = $this->setTask($commandCases);
        }

        return $commandCases;
    }

    private function getChunks($values)
    {
        return array_filter(array_keys($values), fn ($x) => $x != 'all');
    }

    private function setTask($commandCases)
    {
        $tasks = $this->collectTasks($commandCases);

        if (empty($tasks)) return [];

        $tasks = $this->setPart($this->max($tasks, $commandCases), 0, $tasks);

        $tasks = in_array('all', $tasks) ? ['all'] : $tasks;

        return array_filter($tasks);
    }

    private function collectTasks($commandCases)
    {
        $tasks = Arry::unique(Arr::flatten(array_map(
            fn ($x) => Settings::files("{$x}.tasks"),
            Arr::pluck($commandCases['type']['value'], 'type')
        )));

        return array_filter([count($tasks) > 1 ? 'all' : '', ...$tasks]);
    }

    private function max(array $tasks, $commandCases)
    {
        return min(count($tasks), $commandCases['name']['specs']['task']);
    }
}