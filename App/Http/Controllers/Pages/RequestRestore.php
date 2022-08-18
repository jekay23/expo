<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class RequestRestore
{

    public static function prepare(array $requestList)
    {
        if (empty($requestList)) {
            View::render('requestRestore');
        } else {
            View::render('404');
        }
    }
}