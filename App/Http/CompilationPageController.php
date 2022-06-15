<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http;

use Expo\Resources\Views\View;

class CompilationPageController
{
    public static function openPage($requestList, $query)
    {
        if ($requestList) {
            if ('1' == $requestList[0]) {
                View::showView('compilation');
            } else {
                View::showView('404');
            }
        } else {
            View::showView('404'); // duplication now, but will be removed once the DB is connected
        }
    }
}
