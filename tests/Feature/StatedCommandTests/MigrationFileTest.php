<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;
use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FilePathService;

class MigrationFileTest extends BasicFileTestService
{
    public $testType = 'migration';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function migration_default()
    {
        $this->start('', $this->testType, $this->file);
    }

    /** @test */
    public function migration_create()
    {
        $this->start('create', $this->testType, $this->file);
    }
    
    /** @test */
    public function migration_update()
    {
        $this->start('update', $this->testType, $this->file);
    }
}
