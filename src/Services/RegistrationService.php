<?php

namespace Bakgul\FileCreator\Services;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\FileContent\Helpers\Content;
use Bakgul\FileContent\Tasks\ExtendCodeBlock;
use Bakgul\FileContent\Tasks\ExtendCodeLine;
use Bakgul\FileContent\Tasks\GetCodeBlock;
use Bakgul\FileContent\Tasks\GetCodeLine;
use Bakgul\FileContent\Tasks\WriteToFile;
use Bakgul\FileCreator\FileCreator;

class RegistrationService extends FileCreator
{
    protected array $path;
    protected array $request;
    protected array $fileContent;
    protected string $indentation = '';

    protected function isNotRegisterable()
    {
        return $this->isContentNotReady()
            || Arry::contains($this->request['map']['imports'], $this->fileContent);
    }

    protected function isContentNotReady()
    {
        return $this->request['attr']['type'] != 'css' && !isset($this->fileContent) || empty($this->fileContent);
    }

    protected function setRequest(array $request): void
    {
        $this->request = $request;
    }
    
    protected function insertCodeLines(array $specs): void
    {
        [$start, $end, $imports] = GetCodeLine::_($this->fileContent, $specs);

        $this->purifyContent($start, $end);

        $this->regenerateContent($start, $this->makeCodeLines($imports));
    }

    protected function insertCodeBlock(array $specs, string|array $add = '', string $key = ''): void
    {
        [$start, $indentation, $end, $block] = GetCodeBlock::_($this->fileContent, $specs);

        $this->purifyContent($start, $end);

        $this->regenerateContent($start, $this->makeCodeBlock($block, $add, $key, $indentation, $specs));
    }

    protected function purifyContent(int $start, int $end): void
    {
        $this->fileContent = Content::purify($this->fileContent, $start, $end);
    }

    private function makeCodeLines($imports)
    {
        return ExtendCodeLine::_($imports, $this->request['map']['imports']);
    }

    private function makeCodeBlock($block, $add, $key, $indentation, $specs)
    {
        return ExtendCodeBlock::_(
            $block,
            $add ?: $this->request['map'][$key],
            ['base' => $indentation, 'repeat' => $specs['repeat']],
            $specs
        );
    }

    protected function regenerateContent(int $start, array $insert)
    {
        $this->fileContent = Content::regenerate($this->fileContent, $start, $insert);
    }

    protected function getTargetFileContent()
    {
        $this->fileContent = Content::read($this->request['attr']['target_file'], purify: false);
    }

    protected function write()
    {
        WriteToFile::handle($this->fileContent, $this->request['attr']['target_file']);
    }
}