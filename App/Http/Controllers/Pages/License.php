<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class License
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        if (!empty($requestList)) {
            View::render('404');
        } else {
            View::render('license');
        }
    }
}