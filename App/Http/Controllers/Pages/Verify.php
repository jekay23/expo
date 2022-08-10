<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Models\Entities\Tokens;
use Expo\Resources\Views\View;

class Verify
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        if (isset($requestQuery['token']) && empty($requestList)) {
            $token = $requestQuery['token'];
            View::render('verify', compact('token'));
        } else {
            View::render('404');
        }
    }
}
