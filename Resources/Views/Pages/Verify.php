<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class Verify
{
    public static function render(bool &$stickFooter, $data)
    {
        $stickFooter = false;
        View::requireTemplate('verify', 'Page', $data);
    }
}