<?php

namespace Bakgul\FileCreator\Tests\Feature\FileTypeTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTestService;
use Bakgul\Kernel\Helpers\Settings;

class ActionFileTest extends FileTestService
{
    public $testType = 'action';
    public $file = 'delete-user-traces';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function action_default()
    {
        $this->start('', $this->testType, $this->file);
    }
    
    /** @test */
    public function action_static()
    {
        $this->start('static', $this->testType, $this->file);
    }
}
