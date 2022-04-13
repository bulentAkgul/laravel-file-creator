<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;
use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class EventFileTest extends BasicFileTestService
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
