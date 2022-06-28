<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;

class ClassFileTest extends FileTestService
{
    public $testType = 'class';
    public $file = 'long-class-name';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function class_default()
    {
        $this->start('', $this->testType, $this->file, ['subs' => ['user-forms', 'nice-user-forms']]);
    }
    
    /** @test */
    public function class_static()
    {
        $this->start('static', $this->testType, $this->file, ['subs' => ['user-forms', 'nice-user-forms']]);
    }
    
    /** @test */
    public function class_invokable()
    {
        $this->start('invokable', $this->testType, $this->file, ['subs' => ['user-forms', 'nice-user-forms']]);
    }
}
