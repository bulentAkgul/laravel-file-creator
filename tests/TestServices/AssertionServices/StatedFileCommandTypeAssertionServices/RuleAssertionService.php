<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class RuleAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Rules;',
                4 => 'use Illuminate\Contracts\Validation\Rule;',
                6 => 'class {{ name }}Rule implements Rule',
            ],
            [
                'name' => $this->setName($path, 'Rule.php')
            ],
            $path
        );
    }

    public function implicit(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Rules;',
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
