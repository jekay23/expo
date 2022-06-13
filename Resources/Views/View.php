<?php

/**
 * View class
 * In the future it will construct the whole page, but now it's quite primitive
 */

namespace Expo\Resources\Views;

class View
{
    public static function showView($requestView)
    {
        if ($requestView == 'frontpage') {
            require 'frontpageView.php';
        }
    }
}
