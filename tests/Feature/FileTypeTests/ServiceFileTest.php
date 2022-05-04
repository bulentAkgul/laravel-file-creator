<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ServiceFileTest extends FileTestService
{
    public $testType = 'service';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function service_all()
    {
        $this->start('', $this->testType, $this->file);
    }

    /** @test */
    public function service_index()
    {
        $this->start('', $this->testType, $this->file, ['task' => 'index']);
    }

    /** @test */
    public function service_store()
    {
        $this->start('', $this->testType, $this->file, ['task' => 'store']);
    }

    /** @test */
    public function service_create()
    {
        $this->start('', $this->testType, $this->file, ['task' => 'create']);
    }

    /** @test */
    public function service_destroy()
    {
        $this->start('', $this->testType, $this->file, ['task' => 'destroy']);
    }

    /** @test */
    public function service_edit()
    {
        $this->start('', $this->testType, $this->file, ['task' => 'edit']);
    }

    /** @test */
    public function service_show()
    {
        $this->start('', $this->testType, $this->file, ['task' => 'show']);
    }

    /** @test */
    public function service_update()
    {
        $this->start('', $this->testType, $this->file, ['task' => 'update']);
    }

    /** @test */
    public function service_taskless()
    {
        $this->start('', $this->testType, 'paint-color', ['task' => 'taskless']);
    }
}
