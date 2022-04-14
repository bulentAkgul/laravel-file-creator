<?php

return [
    'tasks' => [
        'api' => ['index', 'show', 'store', 'update', 'destroy'],
        'all' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'],
    ],
    'need_parent' => [
        'nested-api' => 'model',
        'nested' => 'model',
        'listener' => 'event',
    ],
    'each_controller_has_route' => true,
    'non_class_types' => ['trait', 'interface', 'enum'],
    'expand_http_in_packages' => true
];
