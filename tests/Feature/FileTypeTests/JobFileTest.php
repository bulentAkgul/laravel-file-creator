<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class JobFileTest extends FileTestService
{
    public $testType = 'job';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function job_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
