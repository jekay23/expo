<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class Footer
{
    public static function render(bool $stickFooter = false)
    {
        $footerExtraClass = '';
        if ($stickFooter) {
            $footerExtraClass .= ' fixed-bottom';
        }
        View::requireTemplate('footer', 'Component', compact('footerExtraClass'));
    }
}
