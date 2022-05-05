<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Required Files Specs
    |--------------------------------------------------------------------------
    |
    | Some file types on "files" array has a specification called "require."
    | Those required files details should be specified here. When 'class' is
    | empty, that file will be generated in the same namespace as the main file.
    |
    */
    [
        'name' => 'user',
        'type' => 'model',
        'variation' => '',
        'path' => 'core/users/src/Models/User.php',
        'class' => "Core\Users\Models\User"
    ],
    [
        'name' => 'event',
        'type' => 'provider',
        'variation' => 'event',
        'path' => '',
        'class' => '',
    ]
];
