<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class NotificationFileTest extends BasicFileTestService
{
    public $testType = 'notification';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function notification_default()
    {
        $this->start('', $this->testType, $this->file);
    }

    /** @test */
    public function notification_markdown()
    {
        $this->start('markdown', $this->testType, $this->file);
    }
}
