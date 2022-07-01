<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class SignIn
{
    public static function render(&$stickFooter)
    {
        $stickFooter = true;
        View::requireTemplate('signIn', 'Page');
    }
}
