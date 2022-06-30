<?php

namespace Expo\Resources\Views\Pages;

class SignIn
{
    public static function render(&$stickFooter)
    {
        $stickFooter = true;
        require 'Templates/signIn.php';
    }
}
