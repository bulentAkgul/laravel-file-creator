<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ResourceFileTest extends FileTestService
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
