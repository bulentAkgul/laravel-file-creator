<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class NotificationFileTest extends FileTestService
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
