<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class TestAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->{Settings::default('files', 'test.variations')}($path);
    }
    
    public function feature(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Tests\Feature;',
                6 => 'use CurrentTest\Testing\Tests\TestCase;',
                8 => 'class {{ name }}Test extends TestCase'
            ],
            [
                'name' => $this->setName($path, 'Test.php')
            ],
            $path
        );
    }

    public function unit(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Tests\Unit;',
                4 => 'use PHPUnit\Framework\TestCase;',
                6 => 'class {{ name }}Test extends TestCase'
            ],
            [
                'name' => $this->setName($path, 'Test.php')
            ],
            $path
        );
    }
}
