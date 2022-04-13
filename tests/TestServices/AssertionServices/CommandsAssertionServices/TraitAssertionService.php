<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class TraitAssertionService extends CommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\\' . Settings::folders('trait') . ';',
                4 => 'trait {{ name }}',
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }
}
