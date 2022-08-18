<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class SignIn
{
    public static function prepare(array $requestList)
    {
        if (!empty($requestList)) {
            View::render('404');
        }
        View::render('signIn');
    }
}
