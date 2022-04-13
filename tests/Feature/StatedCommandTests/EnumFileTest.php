<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class EnumFileTest extends BasicFileTestService
{
    public $testType = 'enum';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function enum_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
