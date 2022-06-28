<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tasks\IsPairFile;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Text;

class ServiceAssertionService extends CommandsAssertionService
{
    private $extra;

    public function default(string $path, string $rootNamespace, array $extra): array
    {
        $this->extra = $extra;

        $name = $this->setName($path, 'Service.php');
        $task = $this->setTask($name);

        $models = $this->isPair() ? $this->getModels() : [];

        return $this->assert(
            $this->setLines($rootNamespace, $models, $task),
            [
                'name' => $n = str_replace($task, '', $name),
                'task' => $task,
                'args' => $this->setServiceArgs($task, $n)
            ],
            $path
        );
    }

    private function isPair(): bool
    {
        return IsPairFile::_('service', $this->extra['files']);
    }

    private function getModels()
    {
        $models = [];

        foreach ($this->getModelPaths() as $path) {
            $models[$this->modelStatus($path)] = $path;
        }

        return $models;
    }

    private function getModelPaths()
    {
        return array_filter($this->extra['paths'], fn ($x) => str_contains($x, 'Models'));
    }

    private function modelStatus(string $path): string
    {
        $name = str_replace('.php', '', Text::getTail($path));

        return Arry::get(array_filter($this->extra['files'], function ($x)  use ($name) {
            return $x['type'] == 'model' && Convention::class($x['name']) == $name;
        }), 0)['order'] == 'parent' ? 'parent' : 'main';
    }

    private function setLines($rootNamespace, $models, $task)
    {
        return $this->namespace($rootNamespace, $task)
            + $this->models($models, $rootNamespace, $task)
            + $this->classDeclaration(count($this->models))
            + $this->methodDeclaration(count($this->models));
    }

    private function namespace($rootNamespace, $task)
    {
        return [2 => $this->setNamespace($rootNamespace, 'src', 'Services' . ($task ? '\{{ name }}Services' : ''))];
    }

    private function models(array $models, string $rootNamespace, string $task): array
    {
        $models['main'] = $this->hasMainModel($task) ? $this->setModel($models['main'], $rootNamespace) : '';
        $models['parent'] = $this->hasParentModel() ? $this->setModel($models['parent'], $rootNamespace) : '';
        
        $this->models = $this->extractNamespaces($models);

        return array_filter([
            4 => $models['parent'] ?: $models['main'],
            5 => $models['parent'] ? $models['main'] : ''
        ]);
    }

    private function extractNamespaces(array $models): array
    {
        return Arry::sort(array_values(array_filter(array_values($models))));
    }

    private function hasMainModel(string $task): bool
    {
        return !in_array($task, ['Create', 'Index', 'Show', 'Store'])
            && IsPairFile::_('service', $this->extra['files'], 'controller');
    }

    private function hasParentModel()
    {
        return str_contains($this->extra['type']['test_variation'], 'nested');
    }

    private function setModel(string $model, string $rootNamespace): string
    {
        return "use {$this->modelNamespace($model,$rootNamespace)};";
    }

    private function modelNamespace($path, $rootNamespace)
    {
        return Path::toNamespace(Path::glue([$rootNamespace ?: 'app', 'Models', str_replace('.php', '', Text::getTail($path))]));
    }

    private function classDeclaration(int $models): array
    {
        return [4 + ($models ? $models + 1 : 0) => 'class {{ task }}{{ name }}Service'];
    }

    private function methodDeclaration(int $models): array
    {
        return [6 + ($models ? $models + 1 : 0) => 'public function handle({{ args }})'];
    }

    private function setServiceArgs(string $task, string $name): string
    {
        if ($this->hasDefaultArg()) return 'mixed $data';

        $parent = Arry::get($this->extra, 'parent')
            ? Arry::get(explode('-p=', $this->extra['append']), 1) ?? ''
            : '';

        $args = implode(', ', array_filter([
            in_array($task, ['Store', 'Update']) ? 'array $request' : '',
            str_contains($this->extra['type']['test_variation'], 'nested') ? $this->instance($parent) : '',
            in_array($task, ['Destroy', 'Edit', 'Update']) ? $this->instance($name) : '',
            in_array($task, ['Create', 'Index', 'Show', 'Store']) ? 'string $' . Convention::var($name) : '',
        ]));

        return $args ?: 'mixed $data';
    }

    private function hasDefaultArg(): bool
    {
        return !IsPairFile::_('service', $this->extra['files'], 'controller');
    }

    private function instance($model)
    {
        return Convention::class($model) . ' $' . Convention::var($model);
    }
}
