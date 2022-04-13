<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class ExceptionFileTest extends BasicFileTestService
{
    public $testType = 'exception';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function exception_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
