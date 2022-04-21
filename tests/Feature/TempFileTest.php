<?php

namespace Bakgul\FileCreator\Tests\Feature;

use Bakgul\Kernel\Tests\Tasks\SetupTest;
use Bakgul\Kernel\Tests\TestCase;

class TempFileTest extends TestCase
{
    /** @test */
    public function event_listener_provider()
    {
        $this->testPackage = (new SetupTest)();
        $this->artisan('create:file dog listener testing -p=animal');
    }
}