<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class CastFileTest extends FileTestService
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
