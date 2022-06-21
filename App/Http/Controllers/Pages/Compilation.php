<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class Compilation
{
    public static function renderPage($requestList, $query)
    {
        if ($requestList) {
            if ('1' == $requestList[0]) {
                View::renderView('compilation');
            } else {
                View::renderView('404');
            }
        } else {
            View::renderView('404'); // TODO duplication now, but will be removed once the DB is connected
        }
    }
}
