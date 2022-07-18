<?php

use Expo\App\Http\Controllers;
use Expo\Routes\Router;

Router::saveCallback('', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Front::prepare($requestList, $requestQuery);
}, false);

Router::saveCallback('profile', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Profile::prepare($requestList, $requestQuery);
}, true);

Router::saveCallback('photo', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Photo::prepare($requestList, $requestQuery);
}, true);

Router::saveCallback('compilation', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Compilation::prepare($requestList, $requestQuery);
}, false);

Router::saveCallback('exhibition', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Exhibition::prepare($requestList, $requestQuery);
}, false);

Router::saveCallback('sign-in', function (array $requestList, array $requestQuery) {
    Controllers\Pages\SignIn::prepare($requestList, $requestQuery);
}, false);

Router::saveCallback('sign-up', function (array $requestList, array $requestQuery) {
    Controllers\Pages\SignUp::prepare($requestList, $requestQuery);
});

Router::saveCallback('api', function (array $requestList, array $requestQuery) {
    Controllers\Api::execute($requestList, $requestQuery);
});

Router::saveCallback('upload', function (array $requestList, array $requestQuery) {
    Controllers\Pages\Upload::prepare($requestList, $requestQuery);
});

Router::saveCallback('404', function () {
    Controllers\Pages\Error404::prepare();
});
