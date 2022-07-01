<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class Compilation
{
    public static function render()
    {
        View::requireTemplate('compilation', 'Page');
    }
}
