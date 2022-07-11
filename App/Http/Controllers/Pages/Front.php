<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class Front
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        View::render('frontpage');
    }
}
