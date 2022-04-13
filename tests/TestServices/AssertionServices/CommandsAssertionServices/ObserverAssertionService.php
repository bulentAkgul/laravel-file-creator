<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class ObserverAssertionService extends CommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Observers;',
                6 => 'class {{ name }}Observer',
                18 => 'public function deleted({{ name }} ${{ var }}): void',
                28 => 'public function forceDeleted({{ name }} ${{ var }}): void',
            ],
            [
                'name' => $name = $this->setName($path, 'Observer.php'),
                'var' => strtolower($name)
            ],
            $path
        );
    }
}
