<?php

namespace Bakgul\FileCreator\Tests\Feature\GeneratedCommandTests;

use Bakgul\Kernel\Tests\Services\TestDataService;
use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTests;
use Bakgul\FileCreator\Tests\TestTasks\SetFileTestCase;

class CreateTestFilesTest extends FileTests
{
    private $specs;

    public function __construct()
    {
        $this->specs = TestDataService::getTestSpecs($this->commandSpecs());

        parent::__construct();
    }

    /** @test */
    public function family_tests___singleName_noTask_noSub_singleChunk_singleType()
    {
        $this->assertTrue(...$this->test($this->setCase([[1, 0, 0, 1], 1]), $this->specs));
    }

    private function setCase(array $case): array
    {
        return (new SetFileTestCase)($case);
    }
    
    private function commandSpecs(): array
    {
        return ['family' => 'tests', 'create' => 'File', 'iteration' => 10];
    }
}
