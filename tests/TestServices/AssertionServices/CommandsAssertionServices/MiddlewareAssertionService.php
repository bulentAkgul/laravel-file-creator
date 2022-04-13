<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class MiddlewareAssertionService extends CommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Middleware;',
                7 => 'class {{ name }}',
                9 => 'public function handle(Request $request, Closure $next): mixed'
            ],
            [
                'name' => $this->setName($path, '.php')
            ],
            $path
        );
    }
}
