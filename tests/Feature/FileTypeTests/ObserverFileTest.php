<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ObserverFileTest extends FileTestService
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
