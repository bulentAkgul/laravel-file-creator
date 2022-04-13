<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class ObserverFileTest extends BasicFileTestService
{
    public $testType = 'observer';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function observer_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
