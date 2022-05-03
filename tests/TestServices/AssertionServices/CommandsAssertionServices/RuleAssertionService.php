<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class RuleAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Rules'),
                4 => 'use Illuminate\Contracts\Validation\Rule;',
                6 => 'class {{ name }}Rule implements Rule',
            ],
            [
                'name' => $this->setName($path, 'Rule.php')
            ],
            $path
        );
    }

    public function implicit(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Rules'),
                4 => 'use Illuminate\Contracts\Validation\ImplicitRule;',
                6 => 'class {{ name }}Rule implements ImplicitRule',
            ],
            [
                'name' => $this->setName($path, 'Rule.php')
            ],
            $path
        );
    }
}
