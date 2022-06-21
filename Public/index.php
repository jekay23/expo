<?php

/**
 * This is the front controller, which routes all requests to their controllers.
 */

namespace Expo\Pub;

use Expo\App\Http\Controllers;
use Expo\Resources\Views\View;
use Expo\Routes\Router;

require __DIR__ . '/../Config/errorReporting.php';

require __DIR__ . '/../Routes/autoloader.php';

Router::route('', function ($requestList, $query) {
    Controllers\Page\Front::openPage($requestList, $query);
});

Router::route('profile', function ($requestList, $query) {
    Controllers\Page\Profile::openPage($requestList, $query);
});

Router::route('photo', function ($requestList, $query) {
    Controllers\Page\Photo::openPage($requestList, $query);
});

Router::route('compilation', function ($requestList, $query) {
    Controllers\Page\Compilation::openPage($requestList, $query);
});

Router::route('exhibition', function ($requestList, $query) {
    Controllers\Page\Exhibition::openPage($requestList, $query);
});

Router::route('sign-in', function ($requestList, $query) {
    Controllers\Page\SignIn::openPage($requestList, $query);
});

Router::route('sign-up', function ($requestList, $query) {
    Controllers\Page\SignUp::openPage($requestList, $query);
});

Router::route('404', function () {
    // ? Should everything be through view without any exceptions?
    View::showView('404');
});

Router::route('phpinfo', function () {
    phpinfo();
});

Router::execute($_SERVER['REQUEST_URI']);
