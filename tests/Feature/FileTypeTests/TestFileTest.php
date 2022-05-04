<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class TestFileTest extends FileTestService
{
    public $testType = 'test';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function test_default()
    {
        $this->start(Settings::files('test.variations.0'), $this->testType, $this->file);
    }

    /** @test */
    public function test_feature_default()
    {
        $this->start('feature', $this->testType, $this->file);
    }

    /** @test */
    public function test_feature_store()
    {
        $this->start('feature', $this->testType, $this->file, ['task' => 'store']);
    }

    /** @test */
    public function test_feature_taskless()
    {
        $this->start('feature', $this->testType, $this->file, ['task' => 'taskless']);
    }

    /** @test */
    public function test_unit()
    {
        $this->start('unit', $this->testType, $this->file);
    }
}
