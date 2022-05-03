<?php

namespace Bakgul\FileCreator\Tests\TestServices\ExecutionServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Pluralizer;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Helpers\Path;
use Carbon\Carbon;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class FilePathService
{
    private static $name = 'post';
    private static $extraName = 'Article';
    private static $family;

    public static function compose(array $options, array $types, string $name = '', mixed $extra = false)
    {
        self::setFamily($options);

        return array_filter(Arry::unique(Arr::flatten(array_map(
            fn ($x) => self::callType($x, $name, $extra),
            array_filter($types, fn ($x) => $x['status'] != 'require')
        ))));
    }

    private static function setFamily(array $options)
    {
        self::$family = match (true) {
            Settings::standalone('package') => 'src',
            Settings::standalone('laravel') => 'app',
            default => $options['unknownPackage'] ? 'app' : 'src'
        };
    }

    private static function callType($type, $name, $extra)
    {
        return self::{$type['type']}(self::setName($name, $type['type']), $extra);
    }

    private static function setName(string $name, string $type)
    {
        return Convention::class($name ?: self::$name, Pluralizer::set($type));
    }

    public static function action($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('action'), 'Actions', "{$name}.php"]))];
    }

    public static function cast($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('cast'), 'Casts', "{$name}Cast.php"]))];
    }

    public static function channel($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('channel'), Settings::folders('channel'), "{$name}Channel.php"]))];
    }

    public static function command($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('command'), 'Commands', "{$name}Command.php"]))];
    }

    public static function controller($name, $extra)
    {
        $extra = is_bool($extra) ? ['parent' => $extra, 'api' => $extra] : $extra;

        return [
            Path::glue(array_filter([
                self::$family, self::http('controller'), 'Controllers', 'Admin', $extra['api'] ? Settings::folders('api') : '', "{$name}Controller.php"
            ])),
            ...self::parents(['migration', 'policy', 'model', 'factory', 'seeder'], $extra['parent'])
        ];
    }

    public static function enum($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('enum'), Settings::folders('enum'), "{$name}.php"]))];
    }

    public static function exception($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('exception'), 'Exceptions', "{$name}Exception.php"]))];
    }

    public static function event($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('event'), 'Events', "{$name}.php"]))];
    }

    public static function factory($name, $extra = false, $policy = false)
    {
        return self::isExtra($extra, 'parent') ? [
            Path::glue(['database', 'factories', self::$extraName . 'Factory.php'])
        ] : [
            Path::glue(['database', 'factories', "{$name}Factory.php"]),
            $policy ? Path::glue(['database', 'factories', 'UserFactory.php']) : '',
        ];
    }

    public static function interface($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('interface'), Settings::folders('interface'), "{$name}.php"]))];
    }

    public static function job($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('job'), 'Jobs', "{$name}Job.php"]))];
    }

    public static function listener($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('listener'), 'Listeners', "{$name}Listener.php"]))];
    }

    public static function mail($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('mail'), 'Mails', "{$name}Mail.php"]))];
    }

    public static function middleware($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('middleware'), 'Middleware', "{$name}.php"]))];
    }

    public static function migration($name, $extra = false, $policy = false)
    {
        $d = Carbon::today()->format('Y_m_d');

        return self::isExtra($extra, 'parent') ? [
            Path::glue(['database', 'migrations', "{$d}_000000_create_" . ConvertCase::snake(Str::plural(self::$extraName)) . "_table.php"])
        ] : [
            Path::glue(['database', 'migrations', "{$d}_000000_create_" . ConvertCase::snake(Str::plural($name)) . "_table.php"]),
            $policy ? Path::glue(['database', 'migrations', "{$d}_000000_create_users_table.php"]) : '',
        ];
    }

    public static function model($name, $extra = false)
    {
        return self::isExtra($extra, 'parent') ? [
            Path::glue(
                array_filter([self::$family, self::http('model'), 'Models', self::$extraName . '.php']),
            )
        ] : [
            Path::glue(
                array_filter([self::$family, self::http('model'), 'Models', "{$name}.php"])
            )
        ];
    }

    public static function notification($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('notification'), 'Notifications', "{$name}Notification.php"]))];
    }

    public static function observer($name, $extra = false)
    {
        return self::isExtra($extra, 'parent') ? [
            Path::glue(
                array_filter([self::$family, self::http('observer'), 'Observers', self::$extraName . 'Observer.php'])
            )
        ] : [
            Path::glue(
                array_filter([self::$family, self::http('observer'), 'Observers', $name . 'Observer.php'])
            )
        ];
    }

    public static function policy($name, $extra = false)
    {
        return self::isExtra($extra, 'parent') ? [
            Path::glue(
                array_filter([self::$family, self::http('policy'), 'Policies', self::$extraName . 'Policy.php'])
            )
        ] : [
            Path::glue(
                array_filter([self::$family, self::http('policy'), 'Policies', $name . 'Policy.php'])
            )
        ];
    }

    public static function provider($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('provider'), 'Providers', "{$name}ServiceProvider.php"]))];
    }

    public static function request($name, $task)
    {
        return array_map(
            fn ($x) => Path::glue(array_filter([self::$family, self::http('request'), 'Requests', $task ? "{$name}Requests" : '', ($task ? ucfirst($x) : '') . "{$name}Request.php"])),
            is_string($task) ? [$task] : Settings::files('request.tasks')
        );
    }

    public static function resource($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('resource'), 'Resources', "{$name}Resource.php"]))];
    }

    public static function rule($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('rule'), 'Rules', "{$name}Rule.php"]))];
    }

    public static function seeder($name, $extra = false, $policy = false)
    {
        return self::isExtra($extra, 'parent') ? [
            Path::glue(['database', 'seeders', self::$extraName . 'Seeder.php'])
        ] : [
            Path::glue(['database', 'seeders', "{$name}Seeder.php"]),
            $policy ? Path::glue(['database', 'factories', 'UserSeeder.php']) : '',
        ];
    }

    public static function service($name, $mode)
    {
        $mode = is_array($mode) ? $mode['api'] : $mode;
        $tasks = Settings::main('tasks');

        return array_map(
            fn ($x) => Path::glue(array_filter([
                self::$family, self::http('service'), 'Services', $x == 'Taskless' ? '' : "{$name}Services", ($x == 'Taskless' ? '' : $x) . "{$name}Service.php"
            ])),
            array_map(
                fn ($x) => ucfirst($x),
                $mode === 'not'
                    ? array_diff($tasks['all'], $tasks['api'])
                    : (is_string($mode) ? [$mode] : $tasks[$mode ? 'api' : 'all'])
            )
        );
    }

    public static function test($name, $variation)
    {
        return ["tests" . Text::wrap(ucfirst($variation)) . "{$name}Test.php"];
    }

    public static function trait($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('trait'), Settings::folders('trait'), "{$name}.php"]))];
    }

    private static function isExtra($extra, $key)
    {
        return is_array($extra) ? Arry::get($extra, $key) ?? false : $extra;
    }

    private static function parents($types, $hasParent)
    {
        return $hasParent ? Arr::flatten(array_map(fn ($x) => self::$x('', true), $types)) : [];
    }

    private static function http($type)
    {
        $schema = Settings::files("{$type}.path_schema");

        if (!str_contains($schema, 'wrapper')) return '';

        return self::$family == 'src' && Settings::main('expand_http_in_packages') ? '' : 'Http';
    }
}
