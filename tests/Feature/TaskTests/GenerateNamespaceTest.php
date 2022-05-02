<?php

namespace Bakgul\FileCreator\Tests\Feature\TaskTests;

use Bakgul\Kernel\Helpers\Path;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\Kernel\Helpers\Text;
use Bakgul\Kernel\Tests\TestCase;
use Bakgul\Kernel\Tasks\GenerateNamespace;
use Bakgul\Kernel\Tests\Services\TestDataService;
use Bakgul\Kernel\Tests\Tasks\SetupTest;

class GenerateNamespaceTest extends TestCase
{
    /** @test */
    public function when_it_is_standalone_laravel_then_the_namespace_will_be_family_regardless_of_the_package_specs()
    {
        $this->testPackage = (new SetupTest)(TestDataService::standalone('sl'), true);

        foreach ([true, false] as $isEmpty) {
            $this->assertEquals('App', GenerateNamespace::_($this->specs($isEmpty, 'src')));
            $this->assertEquals('Tests', GenerateNamespace::_($this->specs($isEmpty, 'tests')));
            $this->assertEquals('Database', GenerateNamespace::_($this->specs($isEmpty, 'database')));
        }
    }

    /** @test */
    public function when_it_is_standalone_package_and_the_tail_is_empty_then_the_namespace_will_be_identity_namespace_regardless_of_the_family_and_package_specs()
    {
        $this->testPackage = (new SetupTest)(TestDataService::standalone('sp'), true);

        foreach ([true, false] as $isEmpty) {
            foreach ($this->families() as $family) {
                $this->assertEquals(
                    Settings::identity('namespace') . Text::append($family == 'src' ? '' : ucfirst($family), '\\'),
                    GenerateNamespace::_($this->specs($isEmpty, $family))
                );
            }
        }
    }

    /** @test */
    public function when_it_is_not_standalone_and_the_package_specs_is_null_then_the_namespace_will_be_family()
    {
        $this->testPackage = (new SetupTest)(TestDataService::standalone('pl'), true);

        $this->assertEquals('App', GenerateNamespace::_($this->specs(true, 'src')));
        $this->assertEquals('Tests', GenerateNamespace::_($this->specs(true, 'tests')));
        $this->assertEquals('Database', GenerateNamespace::_($this->specs(true, 'database')));
    }

    /** @test */
    public function when_it_is_not_standalone_and_the_package_specs_is_provided_then_the_namespace_will_be_package_namespace_plus_family()
    {
        $this->testPackage = (new SetupTest)(TestDataService::standalone('pl'), true);

        $this->assertEquals('Core\Users', GenerateNamespace::_($this->specs(false, 'src')));
        $this->assertEquals('Core\Users\Tests', GenerateNamespace::_($this->specs(false, 'tests')));
        $this->assertEquals('Core\Users\Database', GenerateNamespace::_($this->specs(false, 'database')));
    }

    /** @test */
    public function all_different_scenarios_will_be_tested()
    {
        foreach (['sl', 'sp', 'pl'] as $scenario) {
            $this->testPackage = (new SetupTest)(TestDataService::standalone($scenario), true);

            foreach ([true, false] as $isEmpty) {
                foreach ($this->families() as $family) {
                    foreach ($this->tails($family) as $expect => $tails) {
                        foreach ($tails as $tail) {
                            $this->assertEquals(
                                $this->expect($expect, $family, $scenario, $isEmpty),
                                GenerateNamespace::_(
                                    $this->specs($isEmpty, $family),
                                    Path::glue($tail)
                                )
                            );
                        }
                    }
                }
            }
        }
    }

    private function specs($isEmpty, $family)
    {
        return [
            'root' => $isEmpty ? '' : 'core',
            'package' => $isEmpty ? '' : 'users',
            'family' => $family
        ];
    }

    private function families()
    {
        return ['src', 'tests'];
    }

    private function tails($family)
    {
        return [
            'src' => [
                'Services\UserServices' => [
                    ['services', 'user-services'],
                    ['Services', 'UserServices'],
                ],
                'Http\Resources\UserResources' => [
                    ['http', 'resources', 'user-resources'],
                    ['Http', 'resources', 'UserResources'],
                ]
            ],
            'tests' => [
                'Tests\Feature\MyFeatureTests\IsolationTests' => [
                    ['feature', 'my-feature-tests', 'isolation-tests'],
                    ['Feature', 'MyFeatureTests', 'IsolationTests'],
                ]
            ],
            'database' => [
                'Database\Factories' => [
                    ['database', 'factories'],
                ],
                'Database\Seeders' => [
                    ['Database', 'Seeders'],
                ]
            ]
        ][$family];
    }

    private function expect($expect, $family, $scenario, $isEmpty)
    {
        if ($scenario == 'sl') {
            return $this->glue([$this->family($family, true), $expect]);
        }

        if ($scenario == 'sp') {
            return $this->glue([
                Settings::identity('namespace'),
                $this->family($family, false),
                $expect
            ]);
        }

        return $isEmpty
            ? $this->glue([$this->family($family, true), $expect])
            : $this->glue(['Core\Users', $this->family($family, false), $expect]);
    }

    private function family($family, $isApp)
    {
        return $family == 'src' && $isApp ? 'App' : '';
    }

    private function glue($parts)
    {
        return Path::glue(array_filter($parts), '\\');
    }
}
