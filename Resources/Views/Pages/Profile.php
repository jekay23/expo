<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class Profile
{
    public static function render()
    {
        View::requireTemplate('profile', 'Page');
    }
}
