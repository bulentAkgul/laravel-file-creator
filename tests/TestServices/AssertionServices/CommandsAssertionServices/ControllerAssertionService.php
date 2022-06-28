<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;

class ControllerAssertionService extends CommandsAssertionService
{
    public function invokable(string $path, string $rootNamespace, array $extra): array
    {
        return $this->assert(
            [
                2 => $this->setController($rootNamespace, $extra),
                8 => 'class {{ name }}Controller extends Controller',
                10 => 'public function __invoke(Request $request): Response'
            ],
            $this->map($path),
            $path
        );
    }

    public function plain(string $path, string $rootNamespace, array $extra): array
    {
        return $this->default($path, $rootNamespace, $extra);
    }

    public function api(string $path, string $rootNamespace, array $extra): array
    {
        return $this->assertControllor($path, $rootNamespace, $extra, false);
    }

    public function default(string $path, string $rootNamespace, array $extra): array
    {
        return $this->assertControllor($path, $rootNamespace, $extra, str_contains(Settings::files('controller.variations.0'), 'nested'));
    }

    public function nested(string $path, string $rootNamespace, array $extra): array
    {
        return $this->assertControllor($path, $rootNamespace, $extra, true);
    }

    public function nestedApi(string $path, string $rootNamespace, array $extra): array
    {
        return $this->assertControllor($path, $rootNamespace, $extra, true);
    }

    private function assertControllor($path, $rootNamespace, $extra, $isNested)
    {
        $specs = $this->prepare($extra['paths']);

        $head = $this->assertHead($path, $rootNamespace, $extra, $specs, $isNested);

        if (!$head[0]) return $head;

        return $this->assertMethods($path, $specs, $extra, $isNested);
    }

    private function prepare($paths)
    {
        return [
            'requests' => $r = $this->getUses($paths, 'Requests'),
            'services' => $s = $this->getUses($paths, 'Services'),
            'add' => count($r) + count($s)
        ];
    }

    private function assertHead($path, $rootNamespace, $extra, $specs, $isNested)
    {
        $i = $isNested ? 7 : 6;

        return $this->assert(
            [
                2 => $this->setController($rootNamespace, $extra),
                5 => $this->setModel($rootNamespace, 'name'),
                $i + $specs['add'] + 1 => 'class {{ name }}Controller extends Controller',
            ] + $this->setUses(['Request', 'Http'], $specs['requests'], $rootNamespace, $i)
                + $this->setUses(['Service', ''], $specs['services'], $rootNamespace, $i + count($specs['requests']))
                + ($isNested
                    ? [6 => $this->setModel($rootNamespace, 'parent')]
                    : []
                ),
            $this->map($path, $isNested ? explode('-p=', $extra['append'])[1] : ''),
            $path
        );
    }

    private function assertMethods($path, $specs, $extra, $isNested)
    {
        $content = file($path);

        foreach ($this->tasks($specs['services']) as $task) {
            $index = Arry::containsAt("public function {$task}", $content);
            $found = trim($content[$index]);

            $map = $this->map($path, $this->parent($extra, $isNested), $task);
            $expected = "public function {$task}{$this->args($map, $task, $isNested)}";

            if ($found != $expected) return [false, $this->message($path, $expected, $found, $index + 1)];
        }

        return [true, ''];
    }

    private function parent($extra, $isNested)
    {
        return $isNested ? Arry::get(explode('-p=', $extra['append']), 1) ?? '' : '';
    }

    private function tasks($services)
    {
        return array_map(
            fn ($x) => strtolower(Text::seperate(
                array_reverse(explode(DIRECTORY_SEPARATOR, $x))[0]
            )[0]),
            $services
        );
    }

    private function getUses($paths, $search)
    {
        return array_values(array_filter($paths, fn ($x) => str_contains($x, $search)));
    }

    private function setUses($specs, $paths, $rootNamespace, $i)
    {
        $uses = [];

        foreach ($paths as $j => $path) {
            $uses[$i + $j] = $this->setNamespace($rootNamespace, 'src', [
                "{$specs[0]}s", "{{ name }}{$specs[0]}s", "{$this->task($path)}{{ name }}{$specs[0]}"
            ], 'use', $specs[1]) . ';';
        }

        return $uses;
    }

    private function setController($rootNamespace, $extra)
    {
        return $this->setNamespace($rootNamespace, 'src', ['Controllers', ucfirst(explode(' ', $extra['append'])[0]), $extra['api'] ? 'API' : ''], wrap: 'Http');
    }

    private function setModel($rootNamespace, $key)
    {
        return $this->setNamespace($rootNamespace, 'src', ['Models', "{{ $key }}"], 'use') . ';';
    }

    private function task($path)
    {
        return Text::seperate(array_reverse(explode(DIRECTORY_SEPARATOR, $path))[0])[0];
    }

    private function map($path, $parent = '', $task = '')
    {
        return [
            'name' => $n = $this->setName($path, 'Controller.php'),
            'var' => Convention::var($n),
            'parent' => Convention::class($parent),
            'p_var' => Convention::var($parent),
            'task' => ucfirst($task)
        ];
    }

    private function args(array $map, string $task, bool $isNested)
    {
        return Text::wrap(implode(', ', array_filter([
            in_array($task, ['store', 'update']) ? "{$map['task']}{$map['name']}Request \$request" : '',
            $isNested ? "{$map['parent']} \${$map['p_var']}" : '',
            in_array($task, ['show', 'edit', 'update', 'destroy']) ? "{$map['name']} \${$map['var']}" : '',
            "{$map['task']}{$map['name']}Service \$service"
        ])), '(');
    }
}
