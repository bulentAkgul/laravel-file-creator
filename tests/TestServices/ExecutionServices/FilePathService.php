<?php

namespace Bakgul\FileCreator\Tests\TestServices\ExecutionServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Pluralizer;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Helpers\Path;
use Illuminate\Support\Str;

class FilePathService
{
    private static $name;
    private static $extraName;
    private static $paths;
    private static $extra;
    private static $family;
    private static $mains;

    public static function compose(string $package, array $extra)
    {
        self::reset();

        self::setVariables($package, $extra);

        self::generatePaths($extra);

        return self::setPaths();
    }

    private static function reset()
    {
        self::$name = 'post';
        self::$extraName = 'Article';
        self::$paths = [];
    }

    private static function setPaths()
    {
        return array_filter(Arry::unique(self::$paths));
    }

    private static function setVariables($package, $extra)
    {
        self::$extra = $extra;
        self::$family = self::setFamily($package);
        self::$extraName = self::setExtraName();
        self::$mains = self::setMainFiles();
    }

    private static function setMainFiles()
    {
        return array_values(array_filter(
            self::$extra['files'],
            fn ($x) => $x['status'] == 'main' && $x['order'] == 'main')
        );
    }

    private static function setFamily(string $package)
    {
        return match (Settings::repo()) {
            'sp' => 'src',
            'sl' => 'app',
            default => $package == 'x' ? 'app' : 'src'
        };
    }

    private static function setExtraName()
    {
        return Convention::class(explode(' ', Arry::get(explode('-p=', Arry::get(self::$extra, 'append') ?? ''), 1) ?? '')[0]) ?: self::$extraName;
    }

    private static function generatePaths(array $extra): void
    {
        array_map(
            fn ($file) => self::callType([...$file, ...self::modify($file)]),
            array_filter($extra['files'], fn ($x) => $x['order'] != 'require')
        );
    }

    private static function modify(array $file): array
    {
        return [
            'name' => self::setName($file),
            'subs' => self::setSubs($file),
        ];
    }

    private static function setName(array $file)
    {
        return Convention::class($file['name'] ?: self::$name, Pluralizer::set($file['type']));
    }

    private static function setSubs($file)
    {
        return array_map(fn ($x) => ConvertCase::pascal($x), explode(DIRECTORY_SEPARATOR, $file['subs']));
    }

    private static function callType(array $file): void
    {
        self::$paths = [...self::$paths, self::makePath(self::{$file['type']}($file))];
    }

    private static function makePath(array $parts)
    {
        return Path::glue(array_filter($parts));
    }

    public static function action(array $file)
    {
        return [self::$family, self::http('action'), 'Actions', "{$file['name']}.php"];
    }

    public static function cast(array $file)
    {
        return [self::$family, self::http('cast'), 'Casts', "{$file['name']}Cast.php"];
    }

    public static function channel(array $file)
    {
        return [self::$family, self::http('channel'), Settings::folders('channel'), "{$file['name']}Channel.php"];
    }

    public static function class(array $file)
    {
        return [self::$family, ...$file['subs'], "{$file['name']}.php"];
    }

    public static function command(array $file)
    {
        return [self::$family, self::http('command'), 'Commands', "{$file['name']}Command.php"];
    }

    public static function controller(array $file)
    {
        return [self::$family, self::http('controller'), 'Controllers', 'Admin', self::$extra['api'] ? Settings::folders('api') : '', "{$file['name']}Controller.php"];
    }

    public static function enum(array $file)
    {
        return [self::$family, self::http('enum'), Settings::folders('enum'), "{$file['name']}.php"];
    }

    public static function exception(array $file)
    {
        return [self::$family, self::http('exception'), 'Exceptions', "{$file['name']}Exception.php"];
    }

    public static function event(array $file)
    {
        return [self::$family, self::http('event'), 'Events', "{$file['name']}.php"];
    }

    public static function factory(array $file)
    {
        return ['database', 'factories', "{$file['name']}Factory.php"];
    }

    public static function interface(array $file)
    {
        return [self::$family, self::http('interface'), Settings::folders('interface'), "{$file['name']}.php"];
    }

    public static function job(array $file)
    {
        return [self::$family, self::http('job'), 'Jobs', "{$file['name']}Job.php"];
    }

    public static function listener(array $file)
    {
        return [self::$family, self::http('listener'), 'Listeners', "{$file['name']}Listener.php"];
    }

    public static function mail(array $file)
    {
        return [self::$family, self::http('mail'), 'Mails', "{$file['name']}Mail.php"];
    }

    public static function middleware(array $file)
    {
        return [self::$family, self::http('middleware'), 'Middleware', "{$file['name']}.php"];
    }

    public static function migration(array $file)
    {
        return ['database', 'migrations', "000000_create_" . ConvertCase::snake(Str::plural($file['name'])) . "_table.php"];
    }

    public static function model(array $file)
    {
        return [self::$family, self::http('model'), 'Models', "{$file['name']}.php"];
    }

    public static function notification(array $file)
    {
        return [self::$family, self::http('notification'), 'Notifications', "{$file['name']}Notification.php"];
    }

    public static function observer(array $file)
    {
        return [self::$family, self::http('observer'), 'Observers', "{$file['name']}Observer.php"];
    }

    public static function policy(array $file)
    {
        return [self::$family, self::http('policy'), 'Policies', "{$file['name']}Policy.php"];
    }

    public static function provider(array $file)
    {
        return [self::$family, self::http('provider'), 'Providers', "{$file['name']}ServiceProvider.php"];
    }

    public static function request(array $file)
    {
        return [self::$family, self::http('request'), 'Requests', "{$file['name']}Requests", ucfirst($file['task']) . "{$file['name']}Request.php"];
    }

    public static function resource(array $file)
    {
        return [self::$family, self::http('resource'), 'Resources', "{$file['name']}Resource.php"];
    }

    public static function rule(array $file)
    {
        return [self::$family, self::http('rule'), 'Rules', "{$file['name']}Rule.php"];
    }

    public static function seeder(array $file)
    {
        return ['database', 'seeders', "{$file['name']}Seeder.php"];
    }

    public static function service(array $file)
    {
        return [self::$family, self::http('service'), 'Services', $file['task'] ? "{$file['name']}Services" : '', ucfirst($file['task']) . "{$file['name']}Service.php"];
    }

    public static function test(array $file)
    {
        $type = self::$mains[0]['type'];
        $name = ucfirst($file['task']) . "{$file['name']}Test.php";

        return in_array($type, ['test', 'controller'])
            ? ['tests', ucfirst($file['variation']), $file['task'] ? "{$file['name']}Tests" : '', $name]
            : ['tests', 'Feature', Settings::folders('class-tests'), Convention::class($type) . 'Tests', $name];
    }

    public static function trait(array $file)
    {
        return [self::$family, self::http('trait'), Settings::folders('trait'), "{$file['name']}.php"];
    }

    private static function http($type)
    {
        $schema = Settings::files("{$type}.path_schema");

        if (!str_contains($schema, 'wrapper')) return '';

        return self::$family == 'src' && Settings::main('expand_http_in_packages') ? '' : 'Http';
    }
}
