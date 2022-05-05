<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Main From File Creator
    |--------------------------------------------------------------------------
    |
    | If "each_controller_has_route" is true, then the creted controller will
    | be registered to one of web.php and api.php files.
    |
    | Some files such as controllers or requests are stored in "Http" folder
    | by default. When those files are created in the App namespace, we'll
    | follow the same convention. On the other hand, you can create those
    | files otside the Http folder when they belong to a package. This is
    | controled by altering the value of "expand_http_in_packages."
    |
    */
    'each_controller_has_route' => true,
    'expand_http_in_packages' => true,
];
