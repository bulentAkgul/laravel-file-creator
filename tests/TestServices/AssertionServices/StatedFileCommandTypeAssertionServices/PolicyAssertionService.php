<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class PolicyAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        $name = $this->setName($path, 'Policy.php');
        $i = $name != 'User' ? 1 : 0;

        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Policies;',
                6 => 'use CurrentTest\Testing\Models\{{ name }};',
                8 + $i => 'class {{ name }}Policy',
                17 + $i => 'public function view(User $user, {{ name }} ${{ var }}): Response|bool',
                37 + $i => 'public function restore(User $user, {{ name }} ${{ var }}): Response|bool',
            ],
            [
                'name' => $name,
                'var' => $name == 'User' ? 'policyUser' : strtolower($name)
            ],
            $path
        );
    }
}
