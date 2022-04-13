<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class MiddlewareFileTest extends BasicFileTestService
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
