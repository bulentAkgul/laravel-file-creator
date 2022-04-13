<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class TraitFileTest extends FileTestService
{
    public $testType = 'trait';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function trait_default()
    {
        $this->start('', $this->testType, $this->file);
    }
}
