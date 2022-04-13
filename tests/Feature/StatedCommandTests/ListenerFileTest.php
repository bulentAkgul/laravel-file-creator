<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class ListenerFileTest extends BasicFileTestService
{
    public $testType = 'listener';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function listener_default()
    {
        $this->start('', $this->testType, $this->file, append: "--parent={$this->file}");
    }
}
