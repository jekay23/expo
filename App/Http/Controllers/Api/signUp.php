<?php

namespace Expo\App\Http\Controllers\Api;

use Expo\App\Http\Controllers\PasswordHandler;

if (
    ('http://expo.local/sign-up' === $_SERVER['HTTP_REFERER']) ||
    ('http://62.113.110.35/sign-up' === $_SERVER['HTTP_REFERER'])
) {
    $hash = PasswordHandler::getHash($_POST['password']);
}
