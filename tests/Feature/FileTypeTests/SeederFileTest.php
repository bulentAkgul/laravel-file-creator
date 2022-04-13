<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class SeederFileTest extends FileTestService
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
