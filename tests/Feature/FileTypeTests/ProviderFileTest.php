<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ProviderFileTest extends FileTestService
{
    public $testType = 'provider';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function provider_default()
    {
        $this->start('', $this->testType, $this->file);
    }

    /** @test */
    public function provider_event()
    {
        $this->start('event', $this->testType, 'event');
    }
}
