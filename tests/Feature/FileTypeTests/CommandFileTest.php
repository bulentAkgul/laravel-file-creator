<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class CommandFileTest extends FileTestService
{
    public $testType = 'command';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function command_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
