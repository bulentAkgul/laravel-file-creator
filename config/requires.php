<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Required Files Specs
    |--------------------------------------------------------------------------
    |
    | Some file types on the "files" array have a specification called
    | "require." That required file details should be specified here.
    | When "class" is empty, that file will be generated in the same
    | root namespace as the main file.
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
