<?php

return [
    /*
    |--------------------------------------------------------------------------
    | The List of Supported Tasks
    |--------------------------------------------------------------------------
    |
    | Some file types on the "files" array have tasks. Those tasks must be
    | listed here, too. You can update the list as needed, but adding a new
    | list is not an option.
    |
    */
    'api' => ['index', 'show', 'store', 'update', 'destroy'],
    'all' => ['index', 'show', 'create', 'store', 'edit', 'update', 'destroy'],
];
