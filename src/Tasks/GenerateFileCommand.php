<?php

namespace Bakgul\FileCreator\Tasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;

class GenerateFileCommand
{
    public $seperators;
    public $command;

    public function __construct(private array $specs, private array $attr)
    {
        $this->command = $attr['command'];
        $this->seperators = Settings::seperators();
    }

    public function __invoke(): string
    {
        return 'create:file ' . implode(' ', array_merge(
            $this->generateArguments($this->filter('arguments')),
            $this->generateOptions($this->filter('options')),
        ));
    }

    private function filter(string $by)
    {
        return array_filter($this->attr['signature'], fn ($x) => $x['role'] == $by);
    }

    private function generateArguments(array $args): array
    {
        $parts = [];

        foreach ($args as $title => $details) {
            $parts[] = match ($title) {
                'name' => $this->generateName($details),
                'type' => $this->generateType($details),
                default => $this->command[$title]
            };
        }

        return $parts;
    }

    private function generateName(array $details): string
    {
        return array_reduce($details['schema'], fn ($p, $c) => $p . $this->$c(), '');
    }

    private function generateType(array $details): string
    {
        return array_reduce($details['schema'], fn ($p, $c) => $p . $this->$c(), '');
    }

    protected function subs()
    {
        return Text::prepend(implode($this->seperators['folder'], $this->attr['subs']));
    }

    protected function name()
    {
        return $this->specs['name'] ?: $this->attr['name'];
    }

    protected function task()
    {
        $tasks = [Arry::get($this->specs), 'task']
            ?? Settings::files("{$this->specs['type']}.tasks");

        return empty(array_filter($tasks)) ? '' : $this->append('all');
    }

    protected function type()
    {
        return $this->specs['type'];
    }

    protected function variation()
    {
        return $this->append($this->specs['variation']);
    }

    private function generateOptions(array $opts): array
    {
        $parts = [];

        foreach ($opts as $title => $details) {
            $parts[] = match ($title) {
                'force' => false,
                default => $this->generateOption($title, $details)
            };
        }

        return $parts;
    }

    private function generateOption($title, $details)
    {
        return method_exists($this, $title) ? $this->$title($details) : $this->addOption($title, $details);
    }

    private function addOption($title, $details)
    {
        if (!Arry::get($this->specs, $title)) return '';

        return "--{$title}" . ($details['type'] == 'value' ? "={$this->specs[$title]}" : '');
    }

    private function append(string $value, string $glue = 'modifier'): string
    {
        return Text::append($value, $this->seperators[$glue]);
    }
}
