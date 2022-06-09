<?php

/**
 * This is the front controller, which routes all requests to their controllers.
 */

$route = $_GET['page'];
if ($route == 'favicon.ico') {
    require 'images/favicon.ico';
} elseif ($route == '') {
    $destination = __DIR__ . "/../resources/views/frontpageView.php";
    require $destination;
}
# $controller = new FrontpageController();
# $controller->openFrontpage();
