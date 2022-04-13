<?php

namespace Bakgul\FileCreator\Tests\TestContracts;

interface GenerateExpectation
{
    public function handle(array $command): array;
}