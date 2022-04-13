<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class EnumFileTest extends FileTestService
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
