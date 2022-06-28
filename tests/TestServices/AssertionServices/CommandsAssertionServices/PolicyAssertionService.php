<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\Kernel\Tasks\ConvertCase;

class PolicyAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        $name = $this->setName($path, 'Policy.php');
        $i = $name != 'User' ? 1 : 0;

        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Policies'),
                6 => $this->setNamespace($rootNamespace, 'src', 'Models', 'use') . '\{{ name }};',
                8 + $i => 'class {{ name }}Policy',
                17 + $i => 'public function view(User $user, {{ name }} ${{ var }}): Response|bool',
                37 + $i => 'public function restore(User $user, {{ name }} ${{ var }}): Response|bool',
            ],
            [
                'name' => $name,
                'var' => $name == 'User' ? 'policyUser' : lcfirst($name)
            ],
            $path
        );
    }
}
