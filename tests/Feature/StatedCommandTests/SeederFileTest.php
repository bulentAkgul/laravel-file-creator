<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class SeederFileTest extends BasicFileTestService
{
    public $testType = 'seeder';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function seeder_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
