<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ListenerFileTest extends FileTestService
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
