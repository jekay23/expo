<?php

/**
 * This is the front controller, which routes all requests to their controllers.
 */

namespace Expo\Pub;

use Expo\App\Http\CompilationPageController;
use Expo\App\Http\ExhibitionPageController;
use Expo\App\Http\FrontpageController;
use Expo\App\Http\PhotoPageController;
use Expo\App\Http\ProfilePageController;
use Expo\App\Http\SignInPageController;
use Expo\App\Http\SignUpPageController;
use Expo\Resources\Views\View;
use Expo\Routes\Router;

// display all errors and warnings on the webpage (should be disabled for release)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// autoloader
spl_autoload_register(function ($class) {
    // project-specific namespace prefix
    $prefix = 'Expo\\';

    // base directory for the namespace prefix
    $base_dir = __DIR__ . '/../';

    // does the class use the namespace prefix?
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        // no, move to the next registered autoloader
        return;
    }

    // get the relative class name, which starts after the prefix (on the $len-th symbol)
    $relative_class = substr($class, $len);

    // replace the namespace prefix with the base directory, replace namespace
    // separators with directory separators in the relative class name, append
    // with .php
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // if the file exists, require it
    if (file_exists($file)) {
        require $file;
    }
});

// $routeMain determines which controller we use next, $routeSecondary is given to this controller as argument
Router::route('', function () {
    FrontpageController::openPage();
});

Router::route('profile', function () {
    ProfilePageController::openPage();
});

Router::route('photo', function () {
    PhotoPageController::openPage();
});

Router::route('compilation', function () {
    CompilationPageController::openPage();
});

Router::route('exhibition', function () {
    ExhibitionPageController::openPage();
});

Router::route('sign-in', function () {
    SignInPageController::openPage();
});

Router::route('sign-up', function () {
    SignUpPageController::openPage();
});

Router::route('404', function () {
    View::showView('404');
});

Router::execute($_SERVER['REQUEST_URI']);
