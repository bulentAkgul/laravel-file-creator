<?php

namespace Bakgul\FileCreator\Commands;

use Bakgul\Kernel\Concerns\HasPreparation;
use Bakgul\Kernel\Concerns\HasRequest;
use Bakgul\Kernel\Concerns\Sharable;
use Bakgul\Kernel\Tasks\MakeFileList;
use Bakgul\Evaluator\Concerns\ShouldBeEvaluated;
use Bakgul\Evaluator\Services\FileCommandEvaluationService;
use Bakgul\FileCreator\Services\FileService;
use Bakgul\Kernel\Helpers\Settings;
use Illuminate\Console\Command;

class CreateFileCommand extends Command
{
    use HasPreparation, HasRequest, Sharable, ShouldBeEvaluated;

    protected $signature = '
        create:file
        {name : subs/name:task}
        {type : type:variation}
        {package?}
        {app?}
        {--p|parent= : name:type:variation:grandparent}
        {--t|taskless}
        {--f|force}
    ';
    protected $description = '';

    public function __construct()
    {
        $this->setEvaluator(FileCommandEvaluationService::class);

        parent::__construct();
    }

    public function handle()
    {
        $this->prepareRequest();

        if (Settings::evaluator('evaluate_commands')) {
            $this->evaluate();
            if ($this->stop()) return $this->terminate();
        }

        $this->createFiles(MakeFileList::_($this->request));
    }

    private function createFiles(array $queue)
    {
        array_map(fn ($x) => $this->create($x, $queue), $queue);
    }

    private function create(array $file, array $queue)
    {
        (new FileService)->create($this->makeFileRequest($file, $queue));
    }
}
