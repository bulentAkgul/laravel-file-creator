<?php

namespace Bakgul\FileCreator\Tests\Feature\GeneratedCommandTests;

use Bakgul\Kernel\Tests\Services\TestDataService;
use Bakgul\FileCreator\Tests\TestServices\ExecutionServices\FileTests;
use Bakgul\FileCreator\Tests\TestTasks\SetFileTestCase;

class CreateSrcFilesTest extends FileTests
{
    private $specs;

    public function __construct()
    {
        $this->specs = TestDataService::getTestSpecs($this->commandSpecs());

        parent::__construct();
    }

    /** @test */
    public function family_src___singleName_noTask_noSub_singleChunk_singleType()
    {
        $this->assertTrue(...$this->test($this->setCase([[1, 0, 0, 1], 1]), $this->specs));
    }

    /** @test */
    public function family_src___singleName_singleTask_noSub_singleChunk_singleType()
    {
        $this->assertTrue(...$this->test($this->setCase([[1, 1, 0, 1], 1]), $this->specs));
    }

    /** @test */
    public function family_src___singleName_singleTask_withSubs_singleChunk_singleType()
    {
        $this->assertTrue(...$this->test($this->setCase([[1, 2, 2, 1], 1]), $this->specs));
    }

    /** @test */
    public function family_src___singleName_singleTask_withSubs_multipleChunks_singleType()
    {
        $this->assertTrue(...$this->test($this->setCase([[1, 1, 3, 3], 1]), $this->specs));
    }

    /** @test */
    public function family_src___singleName_multipleTasks_withSubs_inMultipleChunks_singleType()
    {
        $this->assertTrue(...$this->test($this->setCase([[1, 3, 3, 3], 1]), $this->specs));
    }

    /** @test */
    public function family_src___singleName_multipleTasks_noSub_singleChunk_multipleTypes()
    {
        $this->assertTrue(...$this->test($this->setCase([[1, 2, 0, 1], 3]), $this->specs));
    }

    /** @test */
    public function family_src___multipleNames_multipleTasks_withSubs_multipleChunks_multipleTypes()
    {
        $this->assertTrue(...$this->test($this->setCase([[3, 2, 2, 3], 3]), $this->specs));
    }

    private function setCase(array $case): array
    {
        return (new SetFileTestCase)($case);
    }
    
    private function commandSpecs(): array
    {
        return ['family' => 'src', 'create' => 'File', 'iteration' => 50];
    }
}
