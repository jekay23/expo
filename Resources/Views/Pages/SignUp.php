<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\Components\TextField;
use Expo\Resources\Views\View;

class SignUp
{
    public static function render(&$stickFooter)
    {
        $stickFooter = true;
        View::requireTemplate('signUp', 'Page');
    }
}
