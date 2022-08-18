<?php

use Expo\App\Http\Controllers;
use Expo\Routes\ApiRouter;
use Expo\Routes\PageRouter;

PageRouter::saveCallback('', function (array $requestList) {
    Controllers\Pages\Front::prepare($requestList);
});

PageRouter::saveCallback('profile', function (array $requestList) {
    Controllers\Pages\Profile::prepare($requestList);
}, true);

PageRouter::saveCallback('photo', function (array $requestList) {
    Controllers\Pages\Photo::prepare($requestList);
}, true);

PageRouter::saveCallback('compilation', function (array $requestList) {
    Controllers\Pages\Compilation::prepare($requestList);
}, true);

PageRouter::saveCallback('exhibition', function (array $requestList) {
    Controllers\Pages\Exhibition::prepare($requestList);
});

PageRouter::saveCallback('sign-in', function (array $requestList) {
    Controllers\Pages\SignIn::prepare($requestList);
});

PageRouter::saveCallback('sign-up', function (array $requestList) {
    Controllers\Pages\SignUp::prepare($requestList);
});

PageRouter::saveCallback('api', function (array $requestList) {
    ApiRouter::callback($requestList);
});

PageRouter::saveCallback('upload', function (array $requestList) {
    Controllers\Pages\Upload::prepare($requestList);
});

PageRouter::saveCallback('404', function () {
    Controllers\Pages\Error::prepare('404');
});

PageRouter::saveCallback('403', function () {
    Controllers\Pages\Error::prepare('403');
});

PageRouter::saveCallback('503', function () {
    Controllers\Pages\Error::prepare('503');
});

PageRouter::saveCallback('support', function (array $requestList) {
    Controllers\Pages\Support::prepare($requestList);
});

PageRouter::saveCallback('license', function (array $requestList) {
    Controllers\Pages\License::prepare($requestList);
});

PageRouter::saveCallback('faq', function (array $requestList) {
    Controllers\Pages\FAQ::prepare($requestList);
});

PageRouter::saveCallback('verify', function (array $requestList) {
    Controllers\Pages\Verify::prepare($requestList);
});

PageRouter::saveCallback('request-restore', function (array $requestList) {
    Controllers\Pages\RequestRestore::prepare($requestList);
});

PageRouter::saveCallback('restore', function (array $requestList) {
    Controllers\Pages\Restore::prepare($requestList);
});
