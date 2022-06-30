<?php

namespace Expo\Resources\Views\Components;

class Footer
{
    public static function render(bool $stickFooter = false)
    {
        $footerExtraClass = '';
        if ($stickFooter) {
            $footerExtraClass .= ' fixed-bottom';
        }
        require 'Templates/footer.php';
    }
}
