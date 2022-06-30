<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\Components\TextField;

class SignUp
{
    public static function render(&$stickFooter)
    {
        $stickFooter = true;
        require 'Templates/signUp.php';
    }
}
