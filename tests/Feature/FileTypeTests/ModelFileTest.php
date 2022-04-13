<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ModelFileTest extends FileTestService
{
    public $testType = 'model';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function model_default()
    {
        $this->start('', $this->testType, $this->file);
    }

    /** @test */
    public function model_pivot()
    {
        $this->start('pivot', $this->testType, $this->file);
    }
}
