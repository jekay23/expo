<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class Header
{
    public static function render(int $userID = 0, string $currentNavbarLink = null)
    {
        if ($userID != 0) {
            $navbarLink3 = ['href' => "profile/$userID", 'name' => 'Профиль'];
        } else {
            $navbarLink3 = ['href' => "sign-in", 'name' => 'Войти'];
        }
        $navbarLinksExtraClass = [
            'feed' => '',
            'selection' => '',
            'profile' => ''
        ];
        if (isset($currentNavbarLink) && isset($navbarLinksExtraClass[$currentNavbarLink])) {
            $navbarLinksExtraClass[$currentNavbarLink] .= ' active';
        }
        View::requireTemplate('header', 'Component', compact('navbarLink3', 'navbarLinksExtraClass'));
    }
}
