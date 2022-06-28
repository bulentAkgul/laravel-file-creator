<?php

namespace Bakgul\FileCreator\Tests\TestServices\ExecutionServices;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tests\Tasks\SetupTest;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\FileCreator\Tests\TestTasks\ConvertCommandToRequest;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Tasks\MakeFileList;
use Bakgul\Kernel\Tests\Services\TestDataService;
use Bakgul\Kernel\Tests\TestCase;
use Illuminate\Support\Str;

class FileTestService extends TestCase
{
    private $file = 'post';
    private $fakePackage = 'x';
    private $scenario;
    private $package;

    public function __construct(?string $scenario = null, ?bool $hasRoot = null)
    {
        $this->scenario = TestDataService::standalone(
            $scenario ?: Arry::random(['sl', 'sp', 'pl']),
            $hasRoot ?? Arry::random([true, false])
        );

        parent::__construct();
    }

    public function start($variation, $testType, $name = '', $extra = [])
    {
        $this->testPackage = (new SetupTest)($this->scenario);

        $command = $this->command($testType, $variation, $name, $extra);

        $extra['files'] = MakeFileList::_(ConvertCommandToRequest::_($command));

        $this->runCommand($command);

        $this->execute($variation, $testType, $extra);
    }

    private function command(string $type, string $variation, string $name, array $extra)
    {
        $isAlone = array_reduce([$this->scenario['sl'], $this->scenario['sp']], fn ($p, $c) => $p || $c, false);

        $this->package($isAlone);

        $this->setTask($extra);

        return implode(' ', array_filter([
            "create:file",
            $this->name($name, $extra),
            $type . Text::append($variation, ':'),
            $this->package,
            Arry::get($extra, 'append') ?? '',
            $this->task == 'taskless' ? '-t' : '',
        ]));
    }

    private function name(string $name = '', array $extra = [])
    {
        $seperator = Settings::seperators('folder');

        return Text::prepend(implode($seperator, Arry::get($extra, 'subs') ?? []), $seperator)
            . ($name ?: $this->file) . Text::append($this->task == 'taskless' ? '' : $this->task, ':');
    }

    private function package(bool $isAlone): void
    {
        $this->package = !$isAlone
            ? ($this->scenario['root'] ? $this->testPackage['name'] : $this->fakePackage)
            : '';
    }

    private function setTask($extra)
    {
        $this->task = Arry::get($extra, 'task') ?? '';
    }

    private function runCommand($command)
    {
        if ($this->package == $this->fakePackage && Settings::evaluator('evaluate_commands')) {
            return $this->artisan($command)
                ->expectsQuestion(str_replace('{{ package }}', $this->fakePackage, Settings::messages('package.unknown.file')), 'y');
        }

        $this->artisan($command);
    }

    private function execute($variation, $testType, $extra)
    {
        $asserter = new CommandsAssertionService;

        $extra['paths'] = $this->setPaths($extra);

        foreach ($extra['paths'] as $path) {
            $extra['type'] = $this->setType($testType, $variation, $path);

            $fullPath = Path::glue([$this->basePath(), $path]);

            $this->assertFileExists($fullPath);

            $this->assertTrue(...$asserter->handle(
                $fullPath,
                $this->setRootNamespace(),
                $extra
            ));
        }
    }

    private function setPaths(array $extra): array
    {
        return FilePathService::compose($this->package, $extra);
    }

    private function basePath()
    {
        if ($this->package == $this->testPackage['name']) return $this->testPackage['path'];

        return implode(
            DIRECTORY_SEPARATOR,
            array_filter(
                explode(DIRECTORY_SEPARATOR, $this->testPackage['path']),
                fn ($x) => $this->isRequiredPathPart($x)
            )
        );
    }

    private function isRequiredPathPart(string $part): bool
    {
        return !in_array($part, [
            Settings::folders('packages') ?? 'packages',
            $this->testPackage['folder'],
            $this->testPackage['name']
        ]);
    }

    private function setRootNamespace()
    {
        return match (true) {
            $this->scenario['sp'] => Settings::identity('namespace'),
            $this->scenario['sl'] => '',
            $this->package == $this->fakePackage => '',
            default => Path::glue(array_map(
                fn ($x) => ucfirst($x),
                array_filter([$this->testPackage['namespace'], $this->testPackage['name']])
            ), '\\')
        };
    }

    protected function setType(string $testType, string $variation, string $path, string $file = ''): array
    {
        return [
            'name' => $n = $this->setTypeName(Text::serialize($path), $testType),
            'variation' => $this->setVariation($testType, $variation, $file, $n),
            'test_variation' => $variation
        ];
    }

    private function setTypeName(array $path, string $testType)
    {
        foreach ($path as $folder) {
            $container = lcfirst(Str::singular($folder));
            if (Settings::files($container)) return ucfirst($container);

            $container = Arry::find(Settings::folders(), $folder, nullable: false)['key'];
            if ($container) return ucfirst($container);
        }

        return ucfirst($testType);
    }

    private function setVariation(string $testType, string $variation, string $file, string $type): string
    {
        return ConvertCase::camel($this->getVariation($testType, $variation, $file, $type));
    }

    private function getVariation(string $testType, string $variation, string $file, string $type)
    {
        if (strtolower(Str::singular($type)) == $testType) return $variation ?: 'default';

        return Arry::get(explode(':', $file), 2)
            ?: Settings::default('files', strtolower($type) . '.variations')
            ?: 'default';
    }
}
