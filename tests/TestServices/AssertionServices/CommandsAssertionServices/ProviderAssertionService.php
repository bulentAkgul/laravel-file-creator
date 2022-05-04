<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class ProviderAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Providers'),
                6 => 'class {{ name }}ServiceProvider extends ServiceProvider',
            ],
            [
                'name' => $this->setName($path, 'ServiceProvider.php')
            ],
            $path
        );
    }

    public function event(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Providers'),
                6 => 'class EventServiceProvider extends ServiceProvider',
                8 => 'protected $listen = [];'
            ],
            [],
            $path
        );
    }
}
