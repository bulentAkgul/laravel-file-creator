<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class JobFileTest extends BasicFileTestService
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
