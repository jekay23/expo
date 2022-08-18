<?php

namespace Expo\App\Http\Controllers;

class Api
{
    public static function openPageWithUserMessage(
        string $href,
        string $message,
        string $color = 'red',
        string $token = null
    ) {
        $queryArray = ['message' => $message, 'color' => $color];
        if (isset($token)) {
            $queryArray['token'] = $token;
        }
        $uriQuery = http_build_query($queryArray);
        header("Location: $href?$uriQuery");
    }
}
