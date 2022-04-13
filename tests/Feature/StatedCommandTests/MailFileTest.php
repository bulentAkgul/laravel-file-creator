<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class MailFileTest extends BasicFileTestService
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
