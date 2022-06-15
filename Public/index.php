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

// display all errors and warnings on the webpage.
// TODO relocate
// TODO disable for release
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', '1');

require __DIR__ . '/../Routes/autoloader.php';

Router::route('', function ($requestList, $query) {
    FrontpageController::openPage($requestList, $query);
});

Router::route('profile', function ($requestList, $query) {
    ProfilePageController::openPage($requestList, $query);
});

Router::route('photo', function ($requestList, $query) {
    PhotoPageController::openPage($requestList, $query);
});

Router::route('compilation', function ($requestList, $query) {
    CompilationPageController::openPage($requestList, $query);
});

Router::route('exhibition', function ($requestList, $query) {
    ExhibitionPageController::openPage($requestList, $query);
});

Router::route('sign-in', function ($requestList, $query) {
    SignInPageController::openPage($requestList, $query);
});

Router::route('sign-up', function ($requestList, $query) {
    SignUpPageController::openPage($requestList, $query);
});

Router::route('404', function () {
    // ? Should everything be through view without any exceptions?
    View::showView('404');
});

Router::route('phpinfo', function () {
    phpinfo();
});

Router::execute($_SERVER['REQUEST_URI']);
