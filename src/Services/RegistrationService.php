<?php

namespace Bakgul\FileCreator\Services;

use Bakgul\FileCreator\FileCreator;
use Bakgul\FileContent\Tasks\Register;

class RegistrationService extends FileCreator
{
    protected array $path;
    protected array $request;
    protected array $fileContent;
    protected string $indentation = '';

    protected function setRequest(array $request): void
    {
        $this->request = $request;
    }

    protected function register(array $lineSpecs, array $blockSpecs, string $key = 'block', ?string $only = null)
    {
        Register::_($this->request, $lineSpecs, $blockSpecs, $key, $only);
    }
}