<?php

namespace Bakgul\FileCreator\Tests\TestTasks;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;

class StringifyCommand
{
    public $seperators;

    public function __invoke(array $commandCase): string
    {
        $this->seperators = Settings::seperators();

        $command = [];

        foreach ($commandCase as $part => $details) {
            $command[] = $details['role'] == 'arguments'
                ? $this->setArgument($part, $details['value'])
                : $this->setOption($part, $details);
        }

        return implode(' ', array_filter($command));
    }

    public function setArgument($part, $value): string
    {
        return in_array($part, ['name', 'type']) ? $this->$part($value) : $value;
    }

    public function name($value): string
    {
        $chunks = [];

        foreach ($value as $key => $value) {
            if ($key == 'all') continue;

            $tasks = implode($this->seperators['addition'], $value['tasks']);

            $names = implode($this->seperators['part'], array_map(
                fn ($x) =>  $x . Text::append($tasks, $this->seperators['modifier']),
                $value['names']
            ));
            
            $chunks[] = Text::prepend(
                implode($this->seperators['folder'], $value['subs']),
                $this->seperators['folder']
            ) . $names;
        }

        return implode($this->seperators['chunk'], $chunks);
    }

    public function type($value): string
    {
        return implode($this->seperators['part'], array_map(
            fn ($x) => $this->set($this->seperators['modifier'], $x, 'type'),
            $value
        ));
    }

    public function setOption($part, $details)
    {
        $value = $this->set($this->seperators['modifier'], $details['value'], $part);

        return $value
            ? ("--{$part}" . ($details['type'] == 'value' ? "={$value}" : ''))
            : '';
    }

    public function set($glue, $value, $x)
    {
        return is_array($value) ? implode($glue, array_filter($value)) : $value;
    }
}
