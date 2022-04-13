<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class TestFileTest extends BasicFileTestService
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
        $this->start('', $this->testType, $this->file, Settings::files('test.variations.0'));
    }

    /** @test */
    public function test_feature()
    {
        $this->start('feature', $this->testType, $this->file, 'feature');
    }

    /** @test */
    public function test_unit()
    {
        $this->start('unit', $this->testType, $this->file, 'unit');
    }
}
