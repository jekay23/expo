<?php

namespace Expo\App\Http\Controllers\Api;

use Expo\App\Http\Controllers\PasswordHandler;

$hash = PasswordHandler::getHash($_POST['password']);
$post = $_POST;
unset($post['password']);
$post['passwordHash'] = $hash;
list($inputStatus, $error) = UserInputHandler::processPost($post);
if ($inputStatus) {
    list($authStatus, $data) = Authentication::singIn($post);
    if ($authStatus) {
        header("Location: /user/$data");
    } else {
        $uriQuery = http_build_query(['message' => $data, 'color' => 'red']);
        header("Location: /sign-in?$uriQuery");
    }
} else {
    $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
    header("Location: /sign-in?$uriQuery");
}
