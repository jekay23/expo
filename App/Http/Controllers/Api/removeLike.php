<?php

use Expo\App\Http\Controllers\Api\Authentication;
use Expo\App\Http\Controllers\Api\UserInputHandler;
use Expo\App\Models\QueryBuilder;

$userID = Authentication::getUserIdFromCookie();
if (0 == $userID) {
    $uriQuery = http_build_query(['message' => 'Войдите в ваш профиль, чтобы оценивать фото', 'color' => 'red']);
    header("Location: /sign-in?$uriQuery");
    exit;
}
if (empty($_SERVER['QUERY_STRING'])) {
    header("Location: /");
    exit;
}
$uriQuery = [];
parse_str($_SERVER['QUERY_STRING'], $uriQuery);
$validUriQuery = UserInputHandler::processUriQuery($uriQuery);
if ($validUriQuery && isset($uriQuery['photoID'])) {
    $photoID = $uriQuery['photoID'];
    $likeSet = QueryBuilder::checkLike($userID, $photoID);
    if (!$likeSet) {
        $uriQuery = http_build_query(['message' => 'Вы не оценивали это фото', 'color' => 'red']);
        header("Location: /photo/$photoID?$uriQuery");
        exit;
    } else {
        QueryBuilder::removeLike($userID, $photoID);
        header("Location: /photo/$photoID");
        exit;
    }
} else {
    header("Location: /");
    exit;
}
