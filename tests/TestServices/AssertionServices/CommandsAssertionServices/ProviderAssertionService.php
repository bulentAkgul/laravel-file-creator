<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class ProviderAssertionService extends CommandsAssertionService
{
    public function default(string $path): array
    {
        return [true, ''];
    }

    public function event(string $path, $type, $files): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Providers;',
                6 => 'class EventServiceProvider extends ServiceProvider',
                8 => 'protected $listen = [];'
            ],
            [],
            $path
        );
    }

    private function setClassLine($files)
    {
        return [(6 + count(explode(',', $files))) => 'class EventServiceProvider extends ServiceProvider'];
    }

    private function setEventLine(string $files): array
    {
        $files = explode(',', $files);

        return [9 + count($files) => Convention::class($files[0]) . '::class => ['];
    }

    private function setListenerLines(string $files): array
    {
        $files = explode(',', $files);

        $listeners = [];

        foreach (Arry::sort(Arry::drop($files, 'F')) as $i => $listener) {
            $listeners[10 + count($files) + $i] = Convention::class($listener) . 'Listener::class => [],';
        }

        return $listeners;
    }
}
