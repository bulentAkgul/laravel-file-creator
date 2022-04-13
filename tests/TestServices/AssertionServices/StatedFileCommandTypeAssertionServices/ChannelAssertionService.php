<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandTypeAssertionServices;

use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\StatedFileCommandsAssertionService;

class ChannelAssertionService extends StatedFileCommandsAssertionService
{
    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\\' . Settings::folders('channel') . ';',
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
