<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\Html;

class SignIn
{
    public static function prepare(array $requestList)
    {
        if (!empty($requestList)) {
            Html::render('404');
        }
        Html::render('signIn');
    }
}
