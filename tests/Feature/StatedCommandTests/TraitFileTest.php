<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class TraitFileTest extends BasicFileTestService
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
