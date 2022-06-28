<?php

namespace Bakgul\FileCreator\Tests\TestTasks;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;

class ConvertCommandToRequest
{
    const ARGS = ['command', 'name', 'type', 'package', 'app'];
    private static $opts = ['-p' => 'parent', '-t' => 'taskless', '-f' => 'force'];
    private static $args;
    private static $command;

    public static function _(string $command): array
    {
        self::$command = $command;

        self::setArguments();

        [$args, $opts] = self::setParts();

        return [...self::fillMissingArgs($args), ...self::fillMissingOpts($opts)];
    }

    private static function setArguments(): void
    {
        self::$args = Settings::standalone()
            ? array_values(array_filter(self::ARGS, fn ($x) => $x != 'package'))
            : self::ARGS;
    }

    private static function setParts(): array
    {
        return array_reverse([self::setOpts(), self::setArgs()]);
    }

    private static function setOpts(): array
    {
        $opts = [];

        foreach (explode(' ', self::$command) as $part) {
            $opt = explode('=', $part);
            $requestKey = Arry::get(self::$opts, $opt[0]);

            if ($requestKey) {
                self::$command = str_replace($part, '', self::$command);
                $opts[$requestKey] = Arry::get($opt, 1) ?? true;
            }
        }

        return $opts;
    }

    private static function setArgs(): array
    {
        $args = [];

        foreach (array_filter(explode(' ', self::$command)) as $i => $part) {
            $arg = Arry::get(self::$args, $i);
            
            if ($arg) $args[$arg] = $part;
        }

        return $args;
    }

    private static function fillMissingArgs(array $args): array
    {
        return array_merge($args, Arry::combine(array_diff(self::ARGS, array_keys($args))));
    }

    private static function fillMissingOpts(array $opts): array
    {
        return [...Arry::combine(array_values(self::$opts), [], false), ...$opts];
    }
}