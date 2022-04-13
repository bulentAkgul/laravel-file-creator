<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class FactoryFileTest extends FileTestService
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
