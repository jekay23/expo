<?php

use Expo\App\Http\Controllers;
use Expo\Resources\Views\View;
use Expo\Routes\Router;

Router::saveCallback('', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Front::assemble($requestList, $requestQuery);
}, false);

Router::saveCallback('profile', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Profile::assemble($requestList, $requestQuery);
}, true);

Router::saveCallback('photo', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Photo::assemble($requestList, $requestQuery);
}, true);

Router::saveCallback('compilation', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Compilation::assemble($requestList, $requestQuery);
}, true);

Router::saveCallback('exhibition', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Exhibition::assemble($requestList, $requestQuery);
}, false);

Router::saveCallback('sign-in', function (array $requestList, array $requestQuery) {
    Controllers\Pages\SignIn::assemble($requestList, $requestQuery);
}, false);

Router::saveCallback('sign-up', function (array $requestList, array $requestQuery) {
    Controllers\Pages\SignUp::assemble($requestList, $requestQuery);
});

Router::route('404', function () {
    // ? Should everything be through view without any exceptions?
    View::renderView('404');
});
