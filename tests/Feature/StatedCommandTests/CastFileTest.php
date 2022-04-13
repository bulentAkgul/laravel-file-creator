<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class CastFileTest extends BasicFileTestService
{
    public $testType = 'cast';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function cast_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
