<?php

namespace Bakgul\FileCreator\Tests\Feature\TaskTests;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Tests\Concerns\HasTestMethods;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Tests\TestCase;
use Bakgul\Kernel\Tasks\GenerateNamespace;

class GenerateNamespaceTest extends TestCase
{
    use HasTestMethods;

    /** @test */
    public function when_it_is_standalone_laravel_and_family_is_src_then_namespace_will_be_app()
    {
        $this->standalone([false, true]);

        $this->assertEquals('App', GenerateNamespace::_([
            'root' => '',
            'package' => '',
            'family' => 'src'
        ]));
    }

    /** @test */
    public function when_it_is_standalone_laravel_and_family_is_database_then_namespace_will_be_database()
    {
        $this->standalone([false, true]);

        $this->assertEquals('Database', GenerateNamespace::_([
            'root' => '',
            'package' => '',
            'family' => 'database'
        ]));
    }

    /** @test */
    public function when_it_is_standalone_laravel_and_family_is_tests_then_namespace_will_be_tests()
    {
        $this->standalone([false, true]);

        $this->assertEquals('Tests', GenerateNamespace::_([
            'root' => '',
            'package' => '',
            'family' => 'tests'
        ]));
    }

    /** @test */
    public function when_it_is_standalone_laravel_package_and_root_make_no_change()
    {
        $this->standalone([false, true]);

        $this->assertEquals('App', GenerateNamespace::_([
            'root' => 'core',
            'package' => 'users',
            'family' => 'src'
        ]));

        $this->assertEquals('Database', GenerateNamespace::_([
            'root' => 'core',
            'package' => 'users',
            'family' => 'database'
        ]));

        $this->assertEquals('Tests', GenerateNamespace::_([
            'root' => 'core',
            'package' => 'users',
            'family' => 'tests'
        ]));
    }

    /** @test */
    public function when_it_is_standalone_package_and_family_is_src_then_namespace_will_be_standalone_namespace()
    {
        $this->standalone([true, false]);

        $this->assertEquals(
            Settings::identity('namespace'),
            GenerateNamespace::_([
                'root' => '',
                'package' => '',
                'family' => 'src'
            ])
        );
    }

    /** @test */
    public function when_it_is_standalone_package_and_family_is_database_then_namespace_will_be_standalone_namespace_plus_database()
    {
        $this->standalone([true, false]);

        $this->assertEquals(
            Settings::identity('namespace') . '\Database',
            GenerateNamespace::_([
                'root' => '',
                'package' => '',
                'family' => 'database'
            ])
        );
    }

    /** @test */
    public function when_it_is_standalone_package_and_family_is_tests_then_namespace_will_be_standalone_namespace_plus_tests()
    {
        $this->standalone([true, false]);

        $this->assertEquals(
            Settings::identity('namespace') . '\Tests',
            GenerateNamespace::_([
                'root' => '',
                'package' => '',
                'family' => 'tests'
            ])
        );
    }

    /** @test */
    public function when_it_is_standalone_package_package_and_root_make_no_change()
    {
        $this->standalone([true, false]);

        $this->assertEquals(
            Settings::identity('namespace'),
            GenerateNamespace::_([
                'root' => 'core',
                'package' => 'users',
                'family' => 'src'
            ])
        );

        $this->assertEquals(
            Settings::identity('namespace') . '\Database',
            GenerateNamespace::_([
                'root' => 'core',
                'package' => 'users',
                'family' => 'database'
            ])
        );

        $this->assertEquals(
            Settings::identity('namespace') . '\Tests',
            GenerateNamespace::_([
                'root' => 'core',
                'package' => 'users',
                'family' => 'tests'
            ])
        );
    }

    /** @test */
    public function when_it_is_not_standalone_and_family_is_src_the_namespace_will_be_app_if_package_is_null()
    {
        $this->standalone([false, false]);

        $this->assertEquals('App', GenerateNamespace::_([
            'root' => '',
            'package' => '',
            'family' => 'src'
        ]));
    }

    /** @test */
    public function when_it_is_not_standalone_and_family_is_database_the_namespace_will_be_database_if_package_is_null()
    {
        $this->standalone([false, false]);

        $this->assertEquals('Database', GenerateNamespace::_([
            'root' => '',
            'package' => '',
            'family' => 'database'
        ]));
    }

    /** @test */
    public function when_it_is_not_standalone_and_family_is_tests_the_namespace_will_be_tests_if_package_is_null()
    {
        $this->standalone([false, false]);

        $this->assertEquals('Tests', GenerateNamespace::_([
            'root' => '',
            'package' => '',
            'family' => 'tests'
        ]));
    }

    /** @test */
    public function when_it_is_not_standalone_and_package_name_is_provided_and_family_is_src_then_the_namespace_will_be_package_namespace()
    {
        $this->standalone([false, false]);

        $this->assertEquals('Core\Users', GenerateNamespace::_([
            'root' => 'core',
            'package' => 'users',
            'family' => 'src'
        ]));
    }

    /** @test */
    public function when_it_is_not_standalone_and_package_name_is_provided_and_family_is_database_then_the_namespace_will_be_package_namespace_plus_database()
    {
        $this->standalone([false, false]);

        $this->assertEquals('Core\Users\Database', GenerateNamespace::_([
            'root' => 'core',
            'package' => 'users',
            'family' => 'database'
        ]));
    }

    /** @test */
    public function when_it_is_not_standalone_and_package_name_is_provided_and_family_is_tests_then_the_namespace_will_be_package_namespace_plus_tests()
    {
        $this->standalone([false, false]);

        $this->assertEquals('Core\Users\Tests', GenerateNamespace::_([
            'root' => 'core',
            'package' => 'users',
            'family' => 'tests'
        ]));
    }

    /** @test */
    public function when_tail_path_is_provided_its_converted_version_will_be_appended_to_the_namespace()
    {
        $this->standalone([false, false]);

        $this->assertEquals(
            'Core\Users\Tests\Feature\MyFeatureTests\IsolationTests',
            GenerateNamespace::_([
                'root' => 'core',
                'package' => 'users',
                'family' => 'tests'
            ], Path::glue(['Feature', 'MyFeatureTests', 'IsolationTests']))
        );

        $this->assertEquals(
            'Core\Users\Tests\Feature\MyFeatureTests\IsolationTests',
            GenerateNamespace::_([
                'root' => 'core',
                'package' => 'users',
                'family' => 'tests'
            ], Path::glue(['feature', 'my-feature-tests', 'isolation-tests']))
        );
    }
}
