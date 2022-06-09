<?php

/**
 * This is the front controller, which routes all requests to their controllers.
 */

namespace Expo\Pub;

use Expo\App\Http\FrontpageController;

error_reporting(E_ALL);
ini_set('display_errors', 1);

# next step is to make an autoloader somewhere around here

$routes = array(
    'favicon.ico' => 'images/favicon.ico',
    '' => __DIR__ . "/../app/http/FrontpageController.php",
    'exhibition' => __DIR__ . "/../app/http/ExhibitionPageController.php",
    'sign-in' => __DIR__ . "/../app/http/SignInPageController.php",
    'sign-up' => __DIR__ . "/../app/http/SignUpPageController.php",
    'profile' => __DIR__ . "/../app/http/ProfilePageController.php",
    'photo' => __DIR__ . "/../app/http/PhotoPageController.php",
    'compilation' => __DIR__ . "/../app/http/CompilationPageController.php"
);

$route = $_GET['page'];
if ($route == 'favicon.ico') {
    require 'images/favicon.ico';
} elseif ($route == '') {
    require $routes["$route"];
    $controller = new FrontpageController();
    $controller->openFrontpage();
}
# $controller = new FrontpageController();
# $controller->openFrontpage();
