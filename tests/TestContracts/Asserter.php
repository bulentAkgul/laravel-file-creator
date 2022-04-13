<?php

namespace Bakgul\FileCreator\Tests\TestContracts;

interface Asserter
{
    public function handle(array $expectations): array|bool;
}