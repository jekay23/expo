<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class Photo
{
    public static function render(bool &$stickFooter, $photo)
    {
        $varNames = null;
        $stickFooter = true;

        if (isset($photo)) {
            $varNames = ['photo'];
            View::requireTemplate('photo', 'Page', compact($varNames));
        }
    }
}
