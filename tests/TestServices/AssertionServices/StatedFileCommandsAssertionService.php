<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;

class StatedFileCommandsAssertionService
{
    public function handle(string $path, array $type, string $files = '')
    {
        $asserter = ucfirst(
            (Arry::find(Settings::folders(), $type['name']) ?? ['key' => $type['name']])['key']
        );

        return call_user_func_array(
            [new (__NAMESPACE__ . "\StatedFileCommandTypeAssertionServices\\{$asserter}AssertionService"), ConvertCase::camel($type['variation'])],
            [$path, $type, $files]
        );
    }

    protected function setName(string $path, string $search): string
    {
        return str_contains($path, "User{$search}") ? 'User' : str_replace($search, '', Text::getTail($path));
    }

    protected function setTask($name)
    {
        $task = Text::seperate($name)[0];

        return in_array(strtolower($task), Settings::main('tasks.all')) ? $task : '';
    }

    protected function assert(array $expectations, array $map, string $path): array
    {
        $content = file($path);

        foreach ($expectations as $i => $expeced) {
            $expeced = Text::replaceByMap($map, $expeced);

            $found = trim($content[$i]);

            if ($found != $expeced) return [false, $this->message($path, $expeced, $found, $i + 1)];
        }

        return [true, ''];
    }

    private function message($path, $expected, $found, $line)
    {
        return "Expectation on Line {$line}:" . PHP_EOL
             . "\t{$expected}" . PHP_EOL
             . "Found:" . PHP_EOL
             . "\t{$found}" . PHP_EOL
             . "On the file:" . PHP_EOL
             . "\t{$path}";
    }
}
