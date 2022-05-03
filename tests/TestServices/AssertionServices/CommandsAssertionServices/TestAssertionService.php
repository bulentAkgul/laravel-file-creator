<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class TestAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->{Settings::default('files', 'test.variations')}($path, $rootNamespace);
    }
    
    public function feature(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'tests', 'Tests\Feature'),
                6 => 'use CurrentTest\Testing\Tests\TestCase;',
                8 => 'class {{ name }}Test extends TestCase'
            ],
            [
                'name' => $this->setName($path, 'Test.php')
            ],
            $path
        );
    }

    public function unit(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'tests', 'Tests\Unit'),
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
