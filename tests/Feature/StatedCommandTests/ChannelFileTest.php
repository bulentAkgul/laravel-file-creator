<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class ChannelFileTest extends BasicFileTestService
{
    public $testType = 'channel';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function channel_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
