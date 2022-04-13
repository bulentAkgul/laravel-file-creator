<?php

namespace Bakgul\FileCreator\Tests\TestServices\CommandPartServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestServices\CommandServices\FileCommandService;

class FileAppService extends FileCommandService
{
    public function __invoke(array $case): array
    {
        $case['value'] = $case['specs'] ?: Arry::random(array_keys(Settings::apps()));

        return $case;
    }
}