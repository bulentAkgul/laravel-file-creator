<?php

namespace Bakgul\FileCreator\Tests\TestServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Tests\Services\TestDataService;
use Bakgul\FileCreator\Tests\TestContracts\CommandGenerator;
use Illuminate\Support\Arr;

class CommandService
{
    public function __construct(
        protected CommandGenerator $generator,
        protected array $testCase,
        protected array $specs
    ) {}

    public function __invoke(): array
    {
        $commandCases = $this->createCommandCases();

        $commands = [];

        $i = 0;

        while (count($commands) < $this->specs['iteration'] && $i < 50) {
            $command = $this->generator->generate($commandCases, $this->specs);

            if ($this->isUnique($commands, $command)) $commands[] = $command;

            $i++;
        }

        return $commands;
    }

    private function createCommandCases()
    {
        $signature = $this->generator->getSignature();

        foreach (array_keys($signature) as $part) {
            $signature[$part]['specs'] = $this->setValue($part);
        }

        return $signature;
    }

    private function setValue(string $part)
    {
        return Arry::get([...TestDataService::defaults(), ...$this->testCase], $part);
    }

    private function isUnique($commands, $command)
    {
        return !in_array($command['str'], Arr::pluck($commands, 'str'));
    }
}
