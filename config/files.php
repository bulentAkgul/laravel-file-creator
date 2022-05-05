<?php

return [
    /*
    |--------------------------------------------------------------------------
    | PHP Files from File Creator
    |--------------------------------------------------------------------------
    |
    | These are the file types that can be created by "create:file" command.
    | The keys are the types, and the {{ container }} of the path schemas.
    |
    | The schemas are the collections of the placeholders for generating
    | names, paths, and namespaces.
    | 
    | The name count can have one of 3 values:
    |   'S' : the name will be modified to be singular.
    |   'P' : the name will be modified to be plural.
    |   'X' : the name won't be modified, and will be used as it's specified.
    |
    | Variations: The veraitiees of file types. This will be used for setting
    |             accurate stub files. If you need adding a new variation,
    |             create a related stub file. Otherwise the default stub of
    |             that type will be used.
    |
    | Tasks: It is the list of supported tasks by the underlying file type.
    |        You can customize the task lists. If you add any custom task,
    |        make sure it's listed in the main "tasks" array.
    | 
    | Pairs: It is the list of the file types that will also be created
    |        when any file is created. This works recursively. For example,
    |        one of the pairs of controller is model, whose pair is migration,
    |        whose pair is factory. So along with the other pairs, a model,
    |        migration, factory will be generated when you create a controller.
    |        If a pair has tasks like "service," which is controller's pair,
    |        a seperate file will be created for each task. To prevent this
    |        you can add "-t" (taskless) to the command.
    | 
    | Require: If a file needs another one to perform fully, it should be
    |          specified here. This is an array of specifications of a single
    |          file like ["type", "name", "variation"]. For example, policy
    |          requires a user model, event requires EvenServiceProvider.
    |
    | * Use "create:resource" command to create "component" or "livewire"
    |   because these two types will need some extra inputs that can't be
    |   passed through "create:file" command.
    |
    */
    'migration' => [
        'name_schema' => '{{ date }}_000000_create_{{ table }}_table',
        'name_count' => 'P',
        'family' => 'database',
        'convention' => 'snake',
        'path_schema' => '{{ container }}',
        'tasks' => [''],
        'variations' => ['create', 'update'],
        'pairs' => ['model', 'factory', 'seeder'],
    ],
    'factory' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'database',
        'path_schema' => '{{ container }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'seeder' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'database',
        'path_schema' => '{{ container }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'test' => [
        'name_schema' => '{{ task }}{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'tests',
        'path_schema' => '{{ variation }}{{ folder }}{{ subs }}',
        'tasks' => ['', 'index', 'show', 'create', 'store', 'edit', 'update', 'destroy'],
        'variations' => ['feature', 'feature-api', 'unit'],
        'pairs' => [],
    ],
    'action' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => ['', 'static'],
        'pairs' => [],
    ],
    'cast' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'channel' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
        'require' => ['model', 'user', ''],
    ],
    'class' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'command' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'component' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ view }}{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'controller' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ wrapper }}{{ container }}{{ app }}{{ api }}{{ subs }}',
        'tasks' => [''],
        'variations' => ['', 'api', 'invokable', 'model-api', 'model', 'nested-api', 'nested', 'plain'],
        'pairs' => ['model', 'request', 'resource', 'service'],
    ],
    'enum' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'event' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
        'require' => ['provider', 'event', 'event'],
    ],
    'exception' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'interface' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'job' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => ['', 'queued'],
        'pairs' => [],
    ],
    'listener' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'livewire' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ wrapper }}{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => ['', 'inline'],
        'pairs' => [],
    ],
    'mail' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => ['', 'markdown'],
        'pairs' => [],
    ],
    'middleware' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ wrapper }}{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
    'model' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}',
        'tasks' => [''],
        'variations' => ['', 'pivot'],
        'pairs' => ['policy', 'migration'],
    ],
    'notification' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => ['', 'markdown'],
        'pairs' => [],
    ],
    'observer' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => ['model'],
    ],
    'policy' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => ['model'],
        'require' => ['model', 'user', ''],
    ],
    'provider' => [
        'name_schema' => '{{ name }}Service{{ suffix }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ container }}',
        'tasks' => [''],
        'variations' => ['', 'event'],
        'pairs' => [],
    ],
    'resource' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ wrapper }}{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => ['', 'collection'],
        'pairs' => [],
    ],
    'request' => [
        'name_schema' => '{{ task }}{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ wrapper }}{{ container }}{{ folder }}{{ subs }}',
        'tasks' => ['store', 'update'],
        'variations' => [''],
        'pairs' => [],
    ],
    'rule' => [
        'name_schema' => '{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => ['', 'implicit'],
        'pairs' => [],
    ],
    'service' => [
        'name_schema' => '{{ task }}{{ name }}{{ suffix }}',
        'name_count' => 'S',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ folder }}{{ subs }}',
        'tasks' => ['', 'index', 'show', 'create', 'store', 'edit', 'update', 'destroy'],
        'variations' => [''],
        'pairs' => [],
    ],
    'trait' => [
        'name_schema' => '{{ name }}',
        'name_count' => 'X',
        'family' => 'src',
        'path_schema' => '{{ container }}{{ subs }}',
        'tasks' => [''],
        'variations' => [''],
        'pairs' => [],
    ],
];
