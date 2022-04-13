<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class RequestAssertionService extends CommandsAssertionService
{
    public function store(string $path): array
    {
        return $this->default($path);
    }

    public function update(string $path): array
    {
        return $this->default($path);
    }

    public function default(string $path): array
    {
        $name = $this->setName($path, 'Request.php');
        $task = $this->setTask($name);

        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Requests' . ($task ? '\{{ name }}Requests' : '') . ';',
                6 => 'class {{ task }}{{ name }}Request extends FormRequest',
                13 => 'public function rules(): array'
            ],
            [
                'name' => str_replace($task, '', $name),
                'task' => $task,
            ],
            $path
        );
    }
}
