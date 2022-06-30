<?php

namespace Expo\Resources\Views\Components;

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
        require 'Templates/header.php';
    }
}
