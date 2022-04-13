<?php

namespace Bakgul\FileCreator\Tests\TestTasks;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tests\Services\TestDataService;

class SetNamespace
{
    public function __construct(
        private string $family,
        private string $path
    ) {}
    
    public function __invoke(): string
    {
        return $this->setRootNamespace() .  $this->appendFamily() . $this->setFileNamespace();
    }

    private function setRootNamespace(): string
    {
        return TestDataService::package('namespace') . '\\' . ucfirst(TestDataService::package('name'));
    }

    private function appendFamily()
    {
        return $this->family == 'src' ? '\\' : Text::inject(ucfirst($this->family), '\\');
    }

    private function setFileNamespace(): string
    {
        return implode('\\', array_map(
            fn ($x) => ucfirst($x),
            Text::serialize(Text::dropTail(explode($this->family . DIRECTORY_SEPARATOR, $this->path)[1]))
        ));
    }
}