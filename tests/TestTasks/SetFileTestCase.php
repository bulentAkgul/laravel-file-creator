<?php

namespace Bakgul\FileCreator\Tests\TestTasks;

use Bakgul\Kernel\Helpers\Arry;

class SetFileTestCase
{
    const PARTS = ['name', 'task', 'sub', 'chunk'];

    public function __invoke(array $case, array $extra = []): array
    {
        return [
            ...array_combine(['name', 'type'], $this->prepareCase($case)),
            ...$extra
        ];
    }

    private function prepareCase($case): array
    {
        return [Arry::combine(self::PARTS, $case[0], 0), $case[1]];
    }
}