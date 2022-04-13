<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\CollectTypes;
use Bakgul\Kernel\Tests\Tasks\SetupTest;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;
use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FilePathService;

class ControllerFileTest extends FileTestService
{
    public $testType = 'controller';
    public $file = 'post';
    public $parent = 'article';

    public function __construct()
    {
        parent::__construct();
    }
    
    /** @test */
    public function controller_default()
    {
        $this->do('');
    }

    /** @test */
    public function controller_api()
    {
        $this->do('api');
    }

    /** @test */
    public function controller_invokable()
    {
        $this->do('invokable');
    }

    /** @test */
    public function controller_nested_api()
    {
        $this->do('nested-api');
    }

    /** @test */
    public function controller_nested_default()
    {
        $this->do('nested');
    }

    /** @test */
    public function controller_plain()
    {
        $this->do('plain');
    }

    private function do($variation)
    {
        $this->testPackage = (new SetupTest)();
        
        $this->artisan($this->command($variation));

        $this->executeTest($variation);
    }

    private function command(string $variation)
    {
        return "create:file {$this->file} " . $this->type($variation) . " testing admin" . $this->parent($variation);
    }

    private function type($variation)
    {
        return $this->testType . Text::append($variation, ':');
    }

    private function parent($variation)
    {
        return str_contains($variation, 'nested') ? " --parent='{$this->parent}'" : ''; 
    }

    private function executeTest(string $variation)
    {
        $api = str_contains($variation, 'api');

        $asserter = new CommandsAssertionService;

        foreach ($this->setPaths($api, $variation) as $path) {
            $fullPath = Path::glue([$this->testPackage['path'], $path]);

            $this->assertFileExists($fullPath);

            $this->assertTrue(...$asserter->handle(
                $fullPath,
                $this->setType($this->testType, $variation, $path, $this->parent),
                $this->parent
            ));
        }

        foreach ($api ? FilePathService::service($this->file, 'not') : [] as $path) {
            $this->assertFileDoesNotExist(Path::glue([$this->testPackage['path'], $path]));
        }
    }

    private function setPaths(bool $api, string $variation): array
    {
        return FilePathService::compose($this->types($variation), $this->file, $this->extra($api, $variation));
    }

    private function types($variation)
    {
        $parent = Arry::has($variation, Settings::main('need_parent')) ? $this->parent : '';

        return CollectTypes::_([[
            'type' => 'controller',
            'variation' => $variation
        ]], parent: $parent);
    }

    private function extra(bool $api, string $variation): array
    {
        return  ['api' => $api, 'parent' => Arry::has($variation, Settings::main('need_parent'))];
    }
}
