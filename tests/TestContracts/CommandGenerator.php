<?php

namespace Bakgul\FileCreator\Tests\TestContracts;

interface CommandGenerator
{
    public function getSignature(): array;

    public function generate(array $commandCase, array $specs): array;
}