<?php

namespace Expo\App\Http\Controllers\Api;

use Expo\App\Http\Controllers\HashHandler;

$hash = HashHandler::getPasswordHash($_POST['password'], $_POST['email']);
$post = $_POST;
unset($post['password']);
$post['passwordHash'] = $hash;
list($inputStatus, $error) = UserInputHandler::processPost($post);
if ($inputStatus) {
    list($authStatus, $data) = Authentication::signUp($post);
    if ($authStatus) {
        header("Location: /profile/$data");
    } else {
        $uriQuery = http_build_query(['message' => $data, 'color' => 'red']);
        header("Location: /sign-up?$uriQuery");
    }
} else {
    $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
    header("Location: /sign-up?$uriQuery");
}
