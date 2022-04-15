<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

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
        $this->start('', $this->testType, $this->file, $this->extra(false, false), 'admin');
    }

    /** @test */
    public function controller_api()
    {
        $this->start('api', $this->testType, $this->file, $this->extra(true, false), 'admin');
    }

    /** @test */
    public function controller_invokable()
    {
        $this->start('invokable', $this->testType, $this->file, $this->extra(false, false), 'admin');
    }

    /** @test */
    public function controller_nested_api()
    {
        $this->start('nested-api', $this->testType, $this->file, $this->extra(true, true), "admin -p={$this->parent}");
    }

    /** @test */
    public function controller_nested_default()
    {
        $this->start('nested', $this->testType, $this->file, $this->extra(false, true), "admin -p={$this->parent}");
    }

    /** @test */
    public function controller_plain()
    {
        $this->start('plain', $this->testType, $this->file, $this->extra(false, false), 'admin');
    }

    private function extra(bool $api, bool $parent): array
    {
        return  ['api' => $api, 'parent' => $parent];
    }
}
