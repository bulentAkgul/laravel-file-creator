<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class MiddlewareAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Middleware', wrap: 'Http'),
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
