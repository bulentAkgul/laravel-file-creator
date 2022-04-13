<?php

namespace Bakgul\FileCreator;

use Bakgul\Kernel\Functions\CallCommand;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\FileContent\Helpers\Content;

class FileCreator
{
    public function isStandalone($key = '')
    {
        return Settings::standalone($key);
    }

    protected function purify(array $content, int $start, int $end): array
    {
        return Content::purify($content, $start, $end);
    }

    public function stringifyPath(array $path, bool $isFull = false): string
    {
        return Path::stringify($path, $isFull);
    }

    public function artisan(array|string $commands): void
    {
        CallCommand::_($commands);
    }
}