<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class MiddlewareAssertionService extends StatedFileCommandsAssertionService
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
