<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Http\Controllers\Api\Authentication;
use Expo\Resources\Views\View;

class Upload
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        if (!empty($requestList)) {
            View::render('404');
        } elseif (0 === Authentication::getUserIdFromCookie()) {
            View::render('sign-in');
        } else {
            View::render('upload');
        }
    }
}
