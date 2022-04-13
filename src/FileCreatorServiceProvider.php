<?php

namespace Bakgul\FileCreator;

use Bakgul\Kernel\Concerns\HasConfig;
use Illuminate\Support\ServiceProvider;

class FileCreatorServiceProvider extends ServiceProvider
{
    use HasConfig;

    public function boot()
    {
        $this->commands([
            \Bakgul\FileCreator\Commands\CreateFileCommand::class,
        ]);
    }

    public function register()
    {
        $this->registerConfigs(__DIR__ . DIRECTORY_SEPARATOR . '..');
    }
}
