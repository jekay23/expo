<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class Exhibition
{
    public static function assemble(array $requestList, array $requestQuery)
    {
        View::renderView('exhibition');
    }
}
