<?php

namespace Bakgul\FileCreator\Commands;

use Bakgul\Kernel\Concerns\HasPreparation;
use Bakgul\Kernel\Concerns\HasRequest;
use Bakgul\Kernel\Concerns\Sharable;
use Bakgul\Kernel\Tasks\MakeFileList;
use Bakgul\Evaluator\Concerns\ShouldBeEvaluated;
use Bakgul\Evaluator\Services\FileCommandEvaluationService;
use Bakgul\FileCreator\Services\FileService;
use Bakgul\FileHistory\Concerns\HasHistory;
use Bakgul\Kernel\Helpers\Settings;
use Illuminate\Console\Command;

class CreateFileCommand extends Command
{
    use HasHistory, HasPreparation, HasRequest, Sharable, ShouldBeEvaluated;

    protected $signature = '
        create:file
        {name : subs/name:task}
        {type : type:variation}
        {package?}
        {app?}
        {--p|parent=}
        {--t|taskless}
        {--f|force}
    ';
    protected $description = 'This command creates app, database, and test files.';

    protected $arguments = [
        'name' => [
            'Required',
            'subs' => [
                "Optional",
                "You can specify subfolders like 'sub1/sub2/sub3' when you need",
                "a deeper file structure than the file types path_schema provides.",
            ],
            'name' => [
                "Required",
                "The file name without any suffix."
            ],
            'task' => [
                "Optional",
                "You may set one or more tasks with a dot-seperatod fashion like",
                "'users:index' or 'users:index.store.update' If you pass a task",
                "that isn't listed in the tasks list of the given type's and its",
                "pairs' as well as the global list (tasks on 'config/packagify.php'),",
                "it'll be ignored. When the task isn't specified, a seperate file will",
                "be generated for each task of the underlying file type and its pairs."
            ],
        ],
        'type' => [
            'Required',
            'type' => [
                'Required',
                "It should be one of the keys in the 'files' array on",
                "'config/packagify.php' except 'view, css, js, livewire, and",
                "component'. These types will be used by Laravel Resource Creator.",
                "https://github.com/bulentAkgul/laravel-resource-creator",
            ],
            'variation' => [
                'Optional',
                "You may specify which variation in the variations array of the file",
                "type should be created. If it's not set, the default variation, which",
                "is the first item in the array will be used."
            ],
        ],
        'package' => [
            "Optional",
            "It won't be used when you work on a Standalone Laravel or Standalone Package.",
            "If you don't set a valid name, the file will be generated in the App namespace."
        ],
        'app' => [
            "Optional",
            "Some files like controller, may have app specific. App means admin app, web app,",
            "mobile app etc. To create those files in the dedicated app folder, you need to",
            "set the app name. The settings are in 'apps' array on 'packagify.php' file.",
        ],
    ];

    protected $options = [
        'parent' => [
            "To create a nested controller, a parent model, and to create a listener,",
            "a parent event is required."
        ],
        'taskless' => [
            "The file types that have tasks like service, or test, will be generated",
            "as a seperate file for each task unless tasks are specified. But sometimes,",
            "you may want to create a single file without any task. To do that, you need",
            "to append '-t' or '--taskless' to your command. This will cancel the default",
            "behaviour of the task explosion."
        ],
        'force' => [
            "Normally, a file will not be regenerated if it exists. If this options is",
            "passed, a new file will be created anyway."
        ],
    ];

    protected $examples = [];

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

        $this->logFile();

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
