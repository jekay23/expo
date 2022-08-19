<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class SignUp
{
    public static function prepare(array $requestList)
    {
        if (!empty($requestList)) {
            View::render('404');
        }
        View::render('signUp');
    }
}
