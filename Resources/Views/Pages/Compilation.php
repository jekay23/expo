<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\Components\SmallGrid;
use Expo\Resources\Views\View;

class Compilation
{
    public static function render(bool &$stickFooter, array $compilation)
    {
        if (count($compilation['photos']) <= 24) {
            $stickFooter = true;
        }
        View::requireTemplate('compilation', 'Page', compact('compilation'));
        if (count($compilation['photos']) > 0) {
            SmallGrid::render('', $compilation['photos']);
        } else {
            View::requireTemplate('noPhotos', 'Component');
        }
    }
}
