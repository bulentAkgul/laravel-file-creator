<?php

namespace Bakgul\FileCreator\Tests\TestServices;

use Bakgul\Kernel\Helpers\Arry;
use Bakgul\Kernel\Helpers\Settings;
use Bakgul\FileCreator\Tests\TestContracts\Asserter;

class AssertionService
{
    public function __construct(
        protected Asserter $asserter,
        protected array $expectations
    ) {}

    public function __invoke(): array
    {
        foreach ($this->expectations as $expectation) {
            foreach ($this->setExpectation($expectation) as $key) {
                $assertion = $this->$key($expectation, $key, $this->setType($expectation['file']['type']));

                if ($assertion !== true) return [false, $assertion];
            }
        }

        return [true, 'Done'];
    }

    private function setExpectation($expectation)
    {
        return array_filter(array_keys($expectation), fn ($x) => $x != 'file');
    }

    private function setType(string $type): string
    {
        return in_array($type, Settings::main('non_class_types')) ? $type : 'class';
    }

    public function class(array $expectation, string $key, string $type): bool|string
    {
        return $this->isClassSatisfying($expectation, $type)
            ? true
            : $this->makeFailMessage($expectation['path'], strtoupper($key), $expectation[$key], $type);
    }

    public function func(array $expectation, string $key, string $type): bool|string
    {
        return $this->isFuncSatisfying($expectation)
            ? true
            : $this->makeFailMessage($expectation['path'], strtoupper($key), $expectation[$key], 'Schema:');
    }

    public function namespace(array $expectation, string $key, string $type): bool|string
    {
        return $this->isNamespaceSatisfying($expectation, $type)
            ? true
            : $this->makeFailMessage($expectation['path'], strtoupper($key), $expectation[$key], 'namespace');
    }

    public function path(array $expectation, string $key): bool|string
    {
        return file_exists($expectation[$key]) ? true : "Missing File: {$expectation[$key]}";
    }

    public function isClassSatisfying(array $expectation, string $type): bool
    {
        return str_contains(
            $this->getLineFromFile(
                $expectation['path'],
                $type ?: 'return new class'
            ),
            trim("{$type} {$expectation['class']}")
        );
    }

    public function isNamespaceSatisfying(array $expectation, string $type): bool
    {
        return $type == '' || $this->getLineFromFile($expectation['path'], 'namespace') == "namespace {$expectation['namespace']};";
    }

    public function isFuncSatisfying(array $expectation): bool
    {
        return $this->getLineFromFile($expectation['path'], 'Schema:') == $expectation['func'];
    }

    public function makeFailMessage(string $path, string $assertion, string $expectation, string $search): string
    {
        return implode(PHP_EOL, [
            $assertion,
            "  Expectation:" . PHP_EOL . "\t{$expectation}",
            "  Found:" . PHP_EOL . "\t" . $this->getLineFromFile($path, $search),
            "  On:" . PHP_EOL . "\t" . $path
        ]);
    }

    public function getLineFromFile(string $path, string $search): string
    {
        return trim((Arry::find(file($path), $search) ?? ['value' => ''])['value']);
    }
}
