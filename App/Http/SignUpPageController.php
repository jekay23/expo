<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http;

use Expo\Resources\Views\View;

class SignUpPageController
{
    public static function openPage($requestList, $query, $fragment)
    {
        View::showView('signUp');
    }
}
