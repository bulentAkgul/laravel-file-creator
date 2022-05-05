<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices;

use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;

class CommandsAssertionService
{
    public function handle(string $path, string $rootNamespace, array $extra)
    {
        return call_user_func_array(
            [new (__NAMESPACE__ . "\CommandsAssertionServices\\{$extra['type']['name']}AssertionService"), ConvertCase::camel($extra['type']['variation'])],
            [$path, $rootNamespace, $extra]
        );
    }

    protected function setNamespace(
        string $rootNamespace,
        string $family,
        string|array $tail,
        string $head = 'namespace',
        string $wrap = ''
    ): string {
        return "{$head} " . Path::glue(array_filter([
            $rootNamespace,
            $this->family($family, $rootNamespace),
            $this->wrap($wrap, $rootNamespace),
            ...$this->tail($tail),
        ]), '\\') . ($head == 'namespace' ? ';' : '');
    }

    private function family($family, $rootNamespace)
    {
        return ucfirst(match (true) {
            $family == 'src' => $rootNamespace ? '' : 'app',
            default => $family
        });
    }

    private function wrap($wrap, $rootNamespace)
    {
        return ucfirst(match (true) {
            $wrap == '' => '',
            Settings::standalone('laravel') => $wrap,
            !$rootNamespace => $wrap,
            Settings::main('expand_http_in_packages') => '',
            default => $wrap
        });
    }

    private function tail($tail)
    {
        return is_string($tail) ? [$tail] : $tail;
    }

    protected function convertTail(array $subs)
    {
        return array_map(fn ($x) => Convention::namespace($x, null), $subs);
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

    protected function message($path, $expected, $found, $line)
    {
        return "Expectation on Line {$line}:" . PHP_EOL
            . "\t{$expected}" . PHP_EOL
            . "Found:" . PHP_EOL
            . "\t{$found}" . PHP_EOL
            . "On the file:" . PHP_EOL
            . "\t{$path}";
    }
}
