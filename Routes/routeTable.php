<?php

use Expo\App\Http\Controllers;
use Expo\Resources\Views\View;
use Expo\Routes\Router;

Router::route('', function (array $requestList, array $query) {
    Controllers\Pages\Front::renderPage($requestList, $query);
}, false);

Router::route('profile', function (array $requestList, array $query) {
    Controllers\Pages\Profile::renderPage($requestList, $query);
}, true);

Router::route('photo', function (array $requestList, array $query) {
    Controllers\Pages\Photo::renderPage($requestList, $query);
}, true);

Router::route('compilation', function (array $requestList, array $query) {
    Controllers\Pages\Compilation::renderPage($requestList, $query);
}, true);

Router::route('exhibition', function (array $requestList, array $query) {
    Controllers\Pages\Exhibition::renderPage($requestList, $query);
}, false);

Router::route('sign-in', function (array $requestList, array $query) {
    Controllers\Pages\SignIn::renderPage($requestList, $query);
}, false);

Router::route('sign-up', function (array $requestList, array $query) {
    Controllers\Pages\SignUp::renderPage($requestList, $query);
});

Router::route('404', function () {
    // ? Should everything be through view without any exceptions?
    View::renderView('404');
});
