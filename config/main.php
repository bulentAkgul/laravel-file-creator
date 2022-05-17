<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Main From File Creator
    |--------------------------------------------------------------------------
    |
    | If "each_controller_has_route" is true, then the created controller
    | will be registered to one of the "web.php" and "api.php" files.

    | By default, some files such as controllers or requests are stored
    | in the "Http" folder. We'll follow the same convention when those
    | files are created in the App namespace. On the other hand, you can
    | create those files outside the "Http" folder when they belong to
    | a package. This is controlled by altering the value of
    | "expand_http_in_packages."
    |
    */
    'each_controller_has_route' => true,
    'expand_http_in_packages' => true,
];
