<?php

use Expo\App\Http\Controllers;
use Expo\Resources\Views\View;
use Expo\Routes\Router;

Router::route('', function ($requestList, $query) {
    Controllers\Pages\Front::renderPage($requestList, $query);
});

Router::route('profile', function ($requestList, $query) {
    Controllers\Pages\Profile::renderPage($requestList, $query);
});

Router::route('photo', function ($requestList, $query) {
    Controllers\Pages\Photo::renderPage($requestList, $query);
});

Router::route('compilation', function ($requestList, $query) {
    Controllers\Pages\Compilation::renderPage($requestList, $query);
});

Router::route('exhibition', function ($requestList, $query) {
    Controllers\Pages\Exhibition::renderPage($requestList, $query);
});

Router::route('sign-in', function ($requestList, $query) {
    Controllers\Pages\SignIn::renderPage($requestList, $query);
});

Router::route('sign-up', function ($requestList, $query) {
    Controllers\Pages\SignUp::renderPage($requestList, $query);
});

Router::route('404', function () {
    // ? Should everything be through view without any exceptions?
    View::renderView('404');
});

Router::route('phpinfo', function () {
    phpinfo();
});
