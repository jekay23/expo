<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class Restore
{
    public static function render(bool &$stickFooter, $data)
    {
        $stickFooter = false;
        View::requireTemplate('restore', 'Page', $data);
    }
}
