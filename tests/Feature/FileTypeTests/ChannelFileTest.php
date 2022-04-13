<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ChannelFileTest extends FileTestService
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
