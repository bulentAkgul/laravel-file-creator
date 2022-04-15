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
use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Tests\Services\TestDataService;
use Bakgul\Kernel\Tests\TestCase;
use Illuminate\Support\Str;

class FileTestService extends TestCase
{
    private $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    public function start($variation, $testType, $name = '', $extra = false, $append = '')
    {
        foreach (TestDataService::standalone() as $isAlone) {
            foreach ($isAlone['root'] as $hasRoot) {
                $this->testPackage = (new SetupTest)($isAlone);

                $command = $this->command($hasRoot, $isAlone, $testType, $variation, $name, $extra, $append);

                $this->runCommand($command);

                // $this->execute($command['opt'], $variation, $testType, $name, $extra);
            }
        }
    }

    private function command(bool $hasRoot, array $isAlone, string $type, string $variation, string $name, $task, $append)
    {
        $isAlone = array_reduce([$isAlone['sl'], $isAlone['sp']], fn ($p, $c) => $p || $c, false);

        $task = $task == 'taskless' ? $task : (in_array($task, Settings::files("{$type}.tasks")) ? $task : '');

        return [
            'opt' => [
                'unknownPackage' => !$isAlone && !$hasRoot,
            ],
            'str' => "create:file "
                . $this->name($name, $task)
                . " {$type}"
                . Text::append($variation, ':')
                . Text::append($this->package($hasRoot, $isAlone), ' ')
                . Text::append($append, ' ')
                . Text::append($task == 'taskless' ? '-t' : '', ' ')
        ];
    }

    private function name(string $name = '', array|bool|string $task = '')
    {
        return ($name ?: $this->file)
            . Text::append(is_string($task) && $task != 'taskless' ? $task : '', ':');
    }

    private function package($hasRoot, $isAlone)
    {
        return !$isAlone
            ? ($hasRoot ? $this->testPackage['name'] : 'x')
            : '';
    }

    private function runCommand($command)
    {
        if ($command['opt']['unknownPackage']) {
            return $this->artisan($command['str'])
                ->expectsQuestion(str_replace(['{{ package }}', '{{ path }}'], ['x', 'app'], Settings::messages('package.unknown.file')), 'y');
        }

        $this->artisan($command['str']);
    }

    private function execute($options, $variation, $testType, $name, $extra)
    {
        $asserter = new CommandsAssertionService;

        foreach ($this->setPaths($testType, $name, $options, $extra) as $path) {
            $fullPath = Path::glue([$this->basePath($options), $path], DIRECTORY_SEPARATOR);

            $this->assertFileExists($fullPath);

            // $this->assertTrue(...$asserter->handle(
            //     $fullPath,
            //     $this->setType($testType, $variation, $path)
            // ));
        }
    }

    private function setPaths(string $type, string $name, array $options, mixed $extra = false): array
    {
        return FilePathService::compose($options, CollectTypes::_($type), $name ?: $this->file, $extra);
    }

    private function basePath($options)
    {
        if (!$options['missingPackage']) return $this->testPackage['path'];

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
