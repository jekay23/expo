<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class Restore
{

    public static function prepare(array $requestList, array $requestQuery)
    {
        if (isset($requestQuery['token']) && empty($requestList)) {
            $token = $requestQuery['token'];
            View::render('restore', compact('token'));
        } else {
            View::render('404');
        }
    }
}
