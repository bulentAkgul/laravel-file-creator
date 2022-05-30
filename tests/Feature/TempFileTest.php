<?php

namespace Bakgul\FileCreator\Tests\Feature;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tests\Tasks\SetupTest;
use Bakgul\Kernel\Tests\TestCase;

class TempFileTest extends TestCase
{
    /** @test */
    public function event_listener_provider()
    {
        Settings::set('evaluator.evaluate_commands', false);
        $this->testPackage = (new SetupTest)();
        $this->artisan('create:file create-file-list action testing');
    }
}