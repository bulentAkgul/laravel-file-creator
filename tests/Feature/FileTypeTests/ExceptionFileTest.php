<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ExceptionFileTest extends FileTestService
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
