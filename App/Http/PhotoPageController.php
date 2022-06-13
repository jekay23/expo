<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http;

use Expo\Resources\Views\View;

class PhotoPageController
{
    public static function openPage()
    {
        View::showView('photo');
    }
}
