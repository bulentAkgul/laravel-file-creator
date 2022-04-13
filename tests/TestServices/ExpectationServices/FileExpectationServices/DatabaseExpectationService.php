<?php

namespace Bakgul\FileCreator\Tests\TestServices\ExpectationServices\FileExpectationServices;

use Bakgul\Kernel\Helpers\Text;
use Bakgul\FileCreator\Tests\TestServices\ExpectationServices\FileExpectationService;
use Carbon\Carbon;

class DatabaseExpectationService extends FileExpectationService
{
    public function __construct(private array $file, private array $expectation) {}

    public function __invoke()
    {
        $this->expectation['path'] = $this->updatePath();
        
        return $this->modifyForMigration();
    }
    
    private function updatePath(): string
    {
        $glue = Text::inject('database');

        $path = implode($glue, array_map(fn ($x) => lcfirst($x), explode($glue, $this->expectation['path'])));
        
        if ($this->file['type'] == 'migration') {
            $path = Text::changeTail($path, Carbon::today()->format('Y_m_d') . "_000000_create_{$this->expectation['class']}_table.php");
        }

        return $path;
    }

    private function modifyForMigration()
    {
        return $this->file['type'] != 'migration' ? $this->expectation : [
            'file' => $this->expectation['file'],
            'path' => $this->expectation['path'],
            'func' => $this->setFunc()
        ];
    }

    private function setFunc(): string
    {
        $f = $this->file['variation'] == 'update' ? 'table' : 'create';

        return "Schema::{$f}('{$this->expectation['class']}', function (Blueprint \$table) {";
    }
}