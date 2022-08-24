<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class Header
{
    private static array $navbarLinks = [
        'front' => 'feed',
        'profile' => 'profile',
        'signIn' => 'profile',
        'signUp' => 'profile',
        'editProfile' => 'profile',
        'changeAvatar' => 'profile',
        'changePasswordEmail' => 'profile',
        'compilation' => 'selection',
        'verify' => 'profile',
        'requestRestore' => 'profile',
        'restore' => 'profile'
    ];

    public static function render(int $userID, string $requestView)
    {
        if ($userID != 0) {
            $navbarLink3 = ['href' => "profile/$userID", 'name' => 'Профиль'];
        } else {
            $navbarLink3 = ['href' => 'sign-in', 'name' => 'Войти'];
        }
        $navbarLinksExtraClass = [
            'feed' => '',
            'selection' => '',
            'profile' => ''
        ];
        $currentNavbarLink = self::$navbarLinks[$requestView] ?? null;
        if (isset($currentNavbarLink) && isset($navbarLinksExtraClass[$currentNavbarLink])) {
            $navbarLinksExtraClass[$currentNavbarLink] = ' active';
        }
        View::requireTemplate('header', 'Component', compact('navbarLink3', 'navbarLinksExtraClass'));
    }
}
