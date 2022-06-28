<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ControllerFileTest extends FileTestService
{
    public $testType = 'controller';
    public $file = 'post-file';
    public $parent = 'article';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function controller_default()
    {
        $this->start('', $this->testType, $this->file, $this->extra('admin', false, '', false));
    }

    /** @test */
    public function controller_api()
    {
        $this->start('api', $this->testType, $this->file, $this->extra('admin', true, '', false));
    }

    /** @test */
    public function controller_invokable()
    {
        $this->start('invokable', $this->testType, $this->file, $this->extra('admin', false, 'taskless', false));
    }

    /** @test */
    public function controller_nested_api()
    {
        $this->start('nested-api', $this->testType, $this->file, $this->extra("admin -p={$this->parent}", true, '', true));
    }

    /** @test */
    public function controller_nested_default()
    {
        $this->start('nested', $this->testType, $this->file, $this->extra("admin -p={$this->parent}", false, '', true));
    }

    /** @test */
    public function controller_plain()
    {
        $this->start('plain', $this->testType, $this->file, $this->extra('admin', false, '', false));
    }

    private function extra(string $append, bool $api, string $task, bool $parent): array
    {
        return  ['append' => $append, 'api' => $api, 'parent' => $parent, 'task' => $task];
    }
}
