<?php

namespace Bakgul\FileCreator\Tests\TestServices;

use Bakgul\FileCreator\Tests\TestContracts\GenerateExpectation;

class ExpectationService
{
    public function __construct(
        protected GenerateExpectation $generator,
        protected array $command
    ) {}

    public function __invoke(): array
    {
        return $this->generator->handle($this->command);
    }
}