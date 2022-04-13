<?php

namespace Bakgul\FileCreator\Tests\TestServices\CommandPartServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\FileCreator\Tests\TestServices\CommandServices\FileCommandService;

class FileNameService extends FileCommandService
{
    public function __invoke(array $case)
    {
        $case['value'] = ['all' => []];

        foreach (Arry::range($case['specs']['chunk'], 1) as $chunk) {
            $case['value']["chunk-{$chunk}"] = [
                'subs' => $s = $this->setSubFolders($case['specs']['sub']),
                'names' => $this->setNames($case['specs']['name'], $s),
            ];
            
            $case['value']['all'] = array_merge(
                $case['value']['all'],
                $case['value']["chunk-{$chunk}"]['names']
            );
        }

        return $case;
    }

    private function setSubFolders(int $max): array
    {
        return $this->setPart($max, 0);
    }

    private function setNames(int $max, array $subs): array
    {
        return $this->setPart($max, 1, uniques: $subs);
    }
}
