<?php

$post = $_POST;
$userID = \Expo\App\Http\Controllers\Api\Authentication::getUserIdFromCookie();
list($postStatus, $error) = \Expo\App\Http\Controllers\Api\UserInputHandler::processPost($post);
if (!$postStatus) {
    $uriQuery = http_build_query(['message' => $error, 'color' => 'red']);
    header("Location: /profile/$userID/edit?$uriQuery");
    exit;
}
$user = [
    'userID' => $userID,
    'name' => $post['name'],
    'pronoun' => $post['pronoun'],
    'bio' => $post['bio'],
    'contact' => $post['contact']
];
\Expo\App\Models\QueryBuilder::updateProfileData($user);