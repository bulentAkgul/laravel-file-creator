<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class RequestFileTest extends BasicFileTestService
{
    public $testType = 'request';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function request_store()
    {
        $this->start('', $this->testType, $this->file, 'store');
    }

    /** @test */
    public function request_update()
    {
        $this->start('', $this->testType, $this->file, 'update');
    }
}
