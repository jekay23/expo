<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class Header
{
    public static function render(string $currentNavbarLink = null)
    {
        $navbarLinksExtraClass = [
            'feed' => '',
            'selection' => '',
            'profile' => ''
        ];
        if (isset($currentNavbarLink) && isset($navbarLinksExtraClass[$currentNavbarLink])) {
            $navbarLinksExtraClass[$currentNavbarLink] .= ' active';
        }
        View::requireTemplate('header', 'Component', compact('navbarLinksExtraClass'));
    }
}
