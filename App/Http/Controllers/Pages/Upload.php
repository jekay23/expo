<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Http\Controllers\Authentication;
use Expo\Resources\Views\Html;

class Upload
{
    public static function prepare(array $requestList)
    {
        if (!empty($requestList)) {
            Html::render('404');
        } elseif (0 === Authentication::getUserIdFromCookie()) {
            Html::render('sign-in');
        } else {
            Html::render('upload');
        }
    }
}
