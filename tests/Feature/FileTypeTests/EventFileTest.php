<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class EventFileTest extends FileTestService
{
    public $testType = 'event';
    public $file = 'post-published';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function event_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
