<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class RequestAssertionService extends CommandsAssertionService
{
    public function store(string $path, string $rootNamespace): array
    {
        return $this->default($path, $rootNamespace);
    }

    public function update(string $path, string $rootNamespace): array
    {
        return $this->default($path, $rootNamespace);
    }

    public function default(string $path, string $rootNamespace): array
    {
        $name = $this->setName($path, 'Request.php');
        $task = $this->setTask($name);

        return $this->assert(
            [
                2 => $this->setNamespace($rootNamespace, 'src', 'Requests' . ($task ? '\{{ name }}Requests' : ''), wrap: 'Http'),
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
