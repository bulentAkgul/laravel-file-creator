<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class FactoryFileTest extends BasicFileTestService
{
    public $testType = 'factory';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function factory_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
