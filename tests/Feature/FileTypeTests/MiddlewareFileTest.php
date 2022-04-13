<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class MiddlewareFileTest extends FileTestService
{
    public $testType = 'middleware';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function middleware_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
