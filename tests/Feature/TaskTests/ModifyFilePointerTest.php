<?php

namespace Bakgul\FileCreator\Tests\Feature\TaskTests;

use Bakgul\Kernel\Tests\Concerns\HasTestMethods;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tests\TestCase;
use Bakgul\FileCreator\Tasks\ModifyFilePointer;

class ModifyFilePointerTest extends TestCase
{
    use HasTestMethods;

    /** @test */
    public function if_it_is_standalone_package_there_is_no_need_for_modification()
    {
        $this->standalone([true, false]);

        $this->assertEquals(
            Settings::identity('namespace') . "\Requests\UserRequests",
            ModifyFilePointer::namespace($this->payload('Requests', '', Settings::identity('namespace'), 'UserRequests'))
        );
    }

    /** @test */
    public function if_it_is_standalone_laravel_the_namespace_will_be_modified()
    {
        $this->standalone([false, true]);

        $this->assertEquals(
            "App\Http\Requests\UserRequests",
            ModifyFilePointer::namespace($this->payload('Requests', '', 'App', 'UserRequests'))
        );
    }

    /** @test */
    public function if_it_is_not_standalone_and_package_name_is_not_rovided_the_namespace_will_be_modified()
    {
        $this->standalone([false, false]);

        $this->assertEquals(
            "App\Http\Controllers\Admin\API",
            ModifyFilePointer::namespace($this->payload('Controllers', '', 'App', 'Admin\API'))
        );
    }

    /** @test */
    public function if_it_is_not_standalone_and_package_name_is_rovided_then_there_is_no_need_for_modification()
    {
        $this->standalone([false, false]);

        $this->assertEquals(
            "Core\Users\Controllers\Admin\API",
            ModifyFilePointer::namespace($this->payload('Controllers', 'Users', 'Core\Users', 'Admin\API'))
        );
    }

    public function payload($container, $package = '', $root = '', $tail = '')
    {
        return [
            'attr' => [
                'package' => $package
            ],
            'map' => [
                'container' => $container,
                'namespace' => implode("\\", array_filter([$root, $container, $tail])),
            ]
        ];
    }
}
