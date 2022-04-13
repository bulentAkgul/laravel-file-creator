<?php

namespace Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionServices;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tasks\ConvertCase;
use Bakgul\Kernel\Helpers\Convention;
use Bakgul\FileCreator\Tests\TestServices\AssertionServices\CommandsAssertionService;

class ControllerAssertionService extends CommandsAssertionService
{
    public function api(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Controllers\Admin\API;',
                5 => 'use CurrentTest\Testing\Models\{{ name }};',
                7 => 'use CurrentTest\Testing\Requests\{{ name }}Requests\Update{{ name }}Request;',
                8 => 'use CurrentTest\Testing\Services\{{ name }}Services\Index{{ name }}Service;',
                11 => 'use CurrentTest\Testing\Services\{{ name }}Services\Update{{ name }}Service;',
                14 => 'class {{ name }}Controller extends Controller',
                21 => 'public function store(Store{{ name }}Request $request, Store{{ name }}Service $service)',
                26 => 'public function show({{ name }} ${{ var }}, Show{{ name }}Service $service)',
                31 => 'public function update(Update{{ name }}Request $request, {{ name }} ${{ var }}, Update{{ name }}Service $service)'
            ],
            $this->map($path),
            $path
        );
    }

    public function default(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Controllers\Admin;',
                5 => 'use CurrentTest\Testing\Models\{{ name }};',
                7 => 'use CurrentTest\Testing\Requests\{{ name }}Requests\Update{{ name }}Request;',
                8 => 'use CurrentTest\Testing\Services\{{ name }}Services\Index{{ name }}Service;',
                12 => 'use CurrentTest\Testing\Services\{{ name }}Services\Edit{{ name }}Service;',
                13 => 'use CurrentTest\Testing\Services\{{ name }}Services\Update{{ name }}Service;',
                16 => 'class {{ name }}Controller extends Controller',
                28 => 'public function store(Store{{ name }}Request $request, Store{{ name }}Service $service)',
                33 => 'public function show({{ name }} ${{ var }}, Show{{ name }}Service $service)',
                38 => 'public function edit({{ name }} ${{ var }}, Edit{{ name }}Service $service)',
                43 => 'public function update(Update{{ name }}Request $request, {{ name }} ${{ var }}, Update{{ name }}Service $service)'
            ],
            $this->map($path),
            $path
        );
    }

    public function invokable(string $path): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Controllers\Admin;',
                8 => 'class {{ name }}Controller extends Controller',
                10 => 'public function __invoke(Request $request): Response'
            ],
            $this->map($path),
            $path
        );
    }

    public function nested(string $path, array $type, string $parent): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Controllers\Admin;',
                5 => 'use CurrentTest\Testing\Models\{{ name }};',
                6 => 'use CurrentTest\Testing\Models\{{ parent }};',
                7 => 'use CurrentTest\Testing\Requests\{{ name }}Requests\Store{{ name }}Request;',
                9 => 'use CurrentTest\Testing\Services\{{ name }}Services\Index{{ name }}Service;',
                11 => 'use CurrentTest\Testing\Services\{{ name }}Services\Create{{ name }}Service;',
                13 => 'use CurrentTest\Testing\Services\{{ name }}Services\Edit{{ name }}Service;',
                14 => 'use CurrentTest\Testing\Services\{{ name }}Services\Update{{ name }}Service;',
                17 => 'class {{ name }}Controller extends Controller',
                24 => 'public function create({{ parent }} ${{ p_var }}, Create{{ name }}Service $service)',
                29 => 'public function store(Store{{ name }}Request $request, {{ parent }} ${{ p_var }}, Store{{ name }}Service $service)',
                39 => 'public function edit({{ parent }} ${{ p_var }}, {{ name }} ${{ var }}, Edit{{ name }}Service $service)',
                49 => 'public function destroy({{ parent }} ${{ p_var }}, {{ name }} ${{ var }}, Destroy{{ name }}Service $service)'
            ],
            $this->map($path, $parent),
            $path
        );
    }

    public function nestedApi(string $path, array $type, string $parent): array
    {
        return $this->assert(
            [
                2 => 'namespace CurrentTest\Testing\Controllers\Admin\API;',
                5 => 'use CurrentTest\Testing\Models\{{ name }};',
                6 => 'use CurrentTest\Testing\Models\{{ parent }};',
                7 => 'use CurrentTest\Testing\Requests\{{ name }}Requests\Store{{ name }}Request;',
                9 => 'use CurrentTest\Testing\Services\{{ name }}Services\Index{{ name }}Service;',
                11 => 'use CurrentTest\Testing\Services\{{ name }}Services\Store{{ name }}Service;',
                13 => 'use CurrentTest\Testing\Services\{{ name }}Services\Destroy{{ name }}Service;',
                15 => 'class {{ name }}Controller extends Controller',
                22 => 'public function store(Store{{ name }}Request $request, {{ parent }} ${{ p_var }}, Store{{ name }}Service $service)',
                27 => 'public function show({{ parent }} ${{ p_var }}, {{ name }} ${{ var }}, Show{{ name }}Service $service)',
                37 => 'public function destroy({{ parent }} ${{ p_var }}, {{ name }} ${{ var }}, Destroy{{ name }}Service $service)'
            ],
            $this->map($path, $parent),
            $path
        );
    }

    public function plain(string $path): array
    {
        return $this->default($path);
    }

    private function map($path, $parent = '')
    {
        return [
            'name' => $n = $this->setName($path, 'Controller.php'),
            'var' => strtolower($n),
            'parent' => Convention::class($parent),
            'p_var' => ConvertCase::camel($parent)
        ];
    }
}
