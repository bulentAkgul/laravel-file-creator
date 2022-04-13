<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class ResourceFileTest extends BasicFileTestService
{
    public $testType = 'resource';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function resource_default()
    {
        $this->start('', $this->testType, $this->file);
    }

    /** @test */
    public function resource_collection()
    {
        $this->start('collection', $this->testType, $this->file);
    }
}
