<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class ProviderFileTest extends BasicFileTestService
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
