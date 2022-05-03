<?php

namespace Bakgul\FileCreator\Tests\TestServices\ExecutionServices;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\CollectTypes;
use Bakgul\Kernel\Tests\Tasks\SetupTest;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Tasks\ConvertCase;
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
            $scenario ?: Arry::random(['sl', 'sp', 'pl'])[0],
            $hasRoot ?? Arry::random([true, false])[0]
        );

        parent::__construct();
    }

    public function start($variation, $testType, $name = '', $extra = false, $append = '')
    {
        $this->testPackage = (new SetupTest)($this->scenario);

        $command = $this->command($testType, $variation, $name, $extra, $append);

        $this->runCommand($command);

        $this->execute($variation, $testType, $name, $extra);
    }

    private function command(string $type, string $variation, string $name, $task, $append)
    {
        $isAlone = array_reduce([$this->scenario['sl'], $this->scenario['sp']], fn ($p, $c) => $p || $c, false);

        $this->package($isAlone);

        $task = $task == 'taskless' ? $task : (in_array($task, Settings::files("{$type}.tasks")) ? $task : '');

        return implode(' ', array_filter([
            "create:file",
            $this->name($name, $task),
            $type . Text::append($variation, ':'),
            $this->package,
            $append,
            $task == 'taskless' ? '-t' : '',
        ]));
    }

    private function name(string $name = '', array|bool|string $task = '')
    {
        return ($name ?: $this->file)
            . Text::append(is_string($task) && $task != 'taskless' ? $task : '', ':');
    }

    private function package(bool $isAlone): void
    {
        $this->package = !$isAlone
            ? ($this->scenario['root'] ? $this->testPackage['name'] : $this->fakePackage)
            : '';
    }

    private function runCommand($command)
    {
        if ($this->package == $this->fakePackage && Settings::evaluator('evaluate_commands')) {
            return $this->artisan($command)
                ->expectsQuestion(str_replace('{{ package }}', $this->fakePackage, Settings::messages('package.unknown.file')), 'y');
        }

        $this->artisan($command);
    }

    private function execute($variation, $testType, $name, $extra)
    {
        $asserter = new CommandsAssertionService;

        foreach ($this->setPaths($testType, $name, $variation, $extra) as $path) {
            $fullPath = Path::glue([$this->basePath(), $path]);

            $this->assertFileExists($fullPath);
            
            $this->assertTrue(...$asserter->handle(
                $fullPath,
                $this->setRootNamespace(),
                $this->setType($testType, $variation, $path)
            ));
        }
    }

    private function setPaths(string $type, string $name, string $variation, mixed $extra = false): array
    {
        return FilePathService::compose($this->package, CollectTypes::_($type), $variation, $name ?: $this->file, $extra);
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
            Settings::main('packages_root') ?? 'packages',
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
        $path = Text::serialize($path);

        return [
            'name' => $n = Convention::class($path[$path[0] != 'tests']),
            'variation' => $this->setVariation($testType, $variation, $file, $n),
            'test_variation' => $variation
        ];
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
