<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class Photo
{
    public static function render()
    {
        View::requireTemplate('photo', 'Page');
    }
}
