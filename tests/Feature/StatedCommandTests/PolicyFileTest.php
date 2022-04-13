<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class PolicyFileTest extends BasicFileTestService
{
    public $testType = 'policy';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function listener()
    {
        $this->start('', $this->testType, $this->file);
    }
}
