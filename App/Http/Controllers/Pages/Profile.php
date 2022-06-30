<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class Profile
{
    public static function assemble(array $requestList, array $requestQuery)
    {
        if ('1' === $requestList[0]) {
            View::renderView('profile');
        } else {
            View::renderView('404');
        }
    }
}
