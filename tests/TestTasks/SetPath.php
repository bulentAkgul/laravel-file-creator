<?php

namespace Bakgul\FileCreator\Tests\TestTasks;

use Bakgul\Kernel\Helpers\Folder;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;
use Illuminate\Support\Str;

class SetPath
{
    public function __construct(
        private array $specs,
        private array $file,
        private array $command,
        private string $class
    ) {}
    
    public function __invoke(): string
    {
        return $this->setRoot()
            . $this->appendContainer()
            . $this->appendApp()
            . $this->appendFolder()
            . $this->appendVariation()
            . $this->appendApi()
            . $this->appendSubfolders()
            . $this->appendFileName();
    }

    private function setRoot()
    {
        return Path::package($this->command['arr']['package']['value']) . Text::append($this->specs['family']);
    }

    private function appendContainer(): string
    {
        return Text::append($this->specs['family'] != 'tests'
            ? Folder::get($this->file['type'])
            : ''
        );
    }

    private function appendApp()
    {
        return Text::append(str_contains($this->specs['path_schema'], '%APP%')
            ? ConvertCase::pascal(Settings::apps("{$this->command['arr']['app']['value']}.folder"))
            : '');
    }

    private function appendFolder()
    {
        return Text::append(
            str_contains($this->specs['path_schema'], '%FOLDER%') && $this->file['task']
                ? ConvertCase::pascal(Str::singular($this->file['name']) . '-' . Str::plural($this->file['type']))
                : ''
        );
    }

    private function appendVariation()
    {
        return Text::append($this->file['type'] == 'test' ? ucfirst($this->file['variation']) : '');
    }

    private function appendApi()
    {
        return Text::append(
            str_contains($this->specs['path_schema'], '%API%') && str_contains($this->file['variation'], 'api')
                ? Settings::folders('api', nullable: true) ?? strtoupper('api') 
                : ''
        );
    }

    private function appendSubfolders()
    {
        return Text::append(str_contains($this->specs['path_schema'], '%SUBFOLDERS%') ? $this->shapeSubs() : '');
    }

    private function shapeSubs()
    {
        return Path::glue(array_map(fn ($x) => ucfirst($x), $this->file['subs']));
    }

    private function appendFileName(): string
    {
        return Text::append("{$this->class}.php");
    }
}