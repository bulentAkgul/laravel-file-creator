<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class MailFileTest extends FileTestService
{
    public $testType = 'mail';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function mail_default()
    {
        $this->start('', $this->testType, $this->file);
    }

    /** @test */
    public function mail_markdown()
    {
        $this->start('markdown', $this->testType, $this->file);
    }
}
