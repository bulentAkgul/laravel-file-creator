<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class InterfaceFileTest extends BasicFileTestService
{
    public $testType = 'interface';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function interface_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
