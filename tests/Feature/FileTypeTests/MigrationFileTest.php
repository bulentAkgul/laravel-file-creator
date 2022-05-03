<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class MigrationFileTest extends FileTestService
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
