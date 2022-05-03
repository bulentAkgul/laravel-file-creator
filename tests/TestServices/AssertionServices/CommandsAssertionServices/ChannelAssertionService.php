<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class ChannelAssertionService extends CommandsAssertionService
{
    public function default(string $path, string $rootNamespace): array
    {
        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', Settings::folders('channel')),
                6 => 'class {{ name }}Channel',
                13 => 'public function join(User $user): array|bool'
            ],
            [
                'name' => $this->setName($path, 'Channel.php')
            ],
            $path
        );
    }
}
