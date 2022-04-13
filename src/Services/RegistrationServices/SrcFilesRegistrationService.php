<?php

namespace Bakgul\FileCreator\Services\RegistrationServices;

use Bakgul\FileCreator\Services\RegistrationService;

class SrcFilesRegistrationService extends RegistrationService
{
    protected function register(array $lineSpecs = [], array $blockSpecs = [])
    {
        if ($this->isNotRegisterable()) return;

        $this->insertCodeLines($this->setLineSpecs($lineSpecs));
        
        $this->insertCodeBlock($blockSpecs, key: 'block');

        $this->write();
    }

    private function setLineSpecs(array $lineSpecs): array
    {
        return array_merge([
            'start' => ['use', 0],
            'end' => [],
            'isStrict' => false,
            'part' => '',
            'repeat' => 0,
        ], $lineSpecs);
    }
}