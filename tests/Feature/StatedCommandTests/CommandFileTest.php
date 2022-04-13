<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class CommandFileTest extends BasicFileTestService
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
