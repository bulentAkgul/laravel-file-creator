<?php

namespace Bakgul\FileCreator\Tests\Feature\StatedCommandTests;

use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\BasicFileTestService;

class RuleFileTest extends BasicFileTestService
{
    public $testType = 'rule';
    public $file = 'post';

    public function __construct()
    {
        parent::__construct();
    }

    /** @test */
    public function rule_default()
    {
        $this->start('', $this->testType, $this->file);
    }

    /** @test */
    public function rule_implicit()
    {
        $this->start('implicit', $this->testType, $this->file);
    }
}
