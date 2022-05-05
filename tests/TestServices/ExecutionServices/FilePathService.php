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
    private static $variation;
    private static $types;

    public static function compose(string $package, array $types, string $variation, string $name, array $extra)
    {
        self::$variation = $variation;
        self::$types = $types;

        self::setFamily($package);

        return array_filter(Arry::unique(Arr::flatten(array_map(
            fn ($x) => self::callType($x, $name, $extra),
            array_filter($types, fn ($x) => $x['status'] != 'require')
        ))));
    }

    private static function setFamily(string $package)
    {
        self::$family = match (true) {
            Settings::standalone('package') => 'src',
            Settings::standalone('laravel') => 'app',
            default => $package == 'x' ? 'app' : 'src'
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

    public static function class($name, $extra)
    {
        return [Path::glue(array_filter([
            self::$family,
            ...array_map(fn($x) => ConvertCase::pascal($x), $extra['subs']),
            "{$name}.php"
        ]))];
    }

    public static function command($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('command'), 'Commands', "{$name}Command.php"]))];
    }

    public static function controller($name, $extra)
    {
        return [
            Path::glue(array_filter([
                self::$family, self::http('controller'), 'Controllers', 'Admin', $extra['api'] ? Settings::folders('api') : '', "{$name}Controller.php"
            ])),
            ...self::parents(['migration', 'policy', 'model', 'factory', 'seeder'], $extra)
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

    public static function factory($name, $extra = [], $policy = false)
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

    public static function migration($name, $extra = [], $policy = false)
    {
        $d = Carbon::today()->format('Y_m_d');

        return self::isExtra($extra, 'parent') ? [
            Path::glue(['database', 'migrations', "{$d}_000000_create_" . ConvertCase::snake(Str::plural(self::$extraName)) . "_table.php"])
        ] : [
            Path::glue(['database', 'migrations', "{$d}_000000_create_" . ConvertCase::snake(Str::plural($name)) . "_table.php"]),
            $policy ? Path::glue(['database', 'migrations', "{$d}_000000_create_users_table.php"]) : '',
        ];
    }

    public static function model($name, $extra = [])
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

    public static function observer($name, $extra = [])
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

    public static function policy($name, $extra = [])
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

    public static function request($name, $extra)
    {
        $task = Arry::get($extra, 'task') ?: '';
        $tasks = Settings::files('request.tasks');

        return array_map(
            fn ($x) => Path::glue(array_filter([self::$family, self::http('request'), 'Requests', "{$name}Requests", ucfirst($x) . "{$name}Request.php"])),
            $task ? array_intersect(explode('.', $task), $tasks) : $tasks
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

    public static function seeder($name, $extra = [], $policy = false)
    {
        return self::isExtra($extra, 'parent') ? [
            Path::glue(['database', 'seeders', self::$extraName . 'Seeder.php'])
        ] : [
            Path::glue(['database', 'seeders', "{$name}Seeder.php"]),
            $policy ? Path::glue(['database', 'factories', 'UserSeeder.php']) : '',
        ];
    }

    public static function service($name, $extra)
    {
        $typeTasks = Settings::files('service.tasks');
        $task = explode('.', Arry::get($extra, 'task') ?? '');
        $tasks = Settings::tasks(Arry::get($extra, 'api') ? 'api' : 'all');

        return array_map(
            fn ($x) => Path::glue(array_filter([
                self::$family, self::http('service'), 'Services', $x ? "{$name}Services" : '', "{$x}{$name}Service.php"
            ])),
            array_map(
                fn ($x) => ucfirst($x),
                $task[0] == 'taskless'
                    ? (self::$types[0]['type'] == 'controller' ? [] : [''])
                    : (array_filter($task)
                        ? array_intersect($task, $tasks, $typeTasks)
                        : array_intersect($tasks, $typeTasks)
                    )
            )
        );
    }

    public static function test($name, $extra)
    {
        $task = explode('.', Arry::get($extra, 'task') ?? '');

        if (self::$variation == 'unit' || $task[0] == 'taskless') {
            return ["tests" . Text::wrap(ucfirst(self::$variation)) . "{$name}Test.php"];
        }

        $tasks = Settings::files('test.tasks', fn ($x) => $x);

        return array_map(
            fn ($x) => Path::glue(
                ['tests', 'Feature', "{$name}Tests", ucfirst($x) . "{$name}Test.php"]
            ),
            array_filter($task) ? array_intersect($task, $tasks) : $tasks
        );
    }

    public static function trait($name)
    {
        return [Path::glue(array_filter([self::$family, self::http('trait'), Settings::folders('trait'), "{$name}.php"]))];
    }

    private static function isExtra($extra, $key)
    {
        return Arry::get($extra, $key) ?? false;
    }

    private static function parents($types, $extra)
    {
        return Arry::get($extra, 'parent') ? Arr::flatten(array_map(
            fn ($x) => self::$x('', $extra),
            $types
        )) : [];
    }

    private static function http($type)
    {
        $schema = Settings::files("{$type}.path_schema");

        if (!str_contains($schema, 'wrapper')) return '';

        return self::$family == 'src' && Settings::main('expand_http_in_packages') ? '' : 'Http';
    }
}
