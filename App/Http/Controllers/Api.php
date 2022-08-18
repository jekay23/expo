<?php

namespace Expo\App\Http\Controllers;

use Exception;
use Expo\App\Http\Controllers\Api\AdminActions;
use Expo\App\Http\Controllers\Api\UserActions;
use Expo\Resources\Views\View;

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
