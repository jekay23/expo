<?php

/**
 * View class
 * In the future it will construct the whole page, but now it's quite primitive
 */

namespace Expo\Resources\Views;

use Expo\Resources\Views\Pages;

class View
{
    private static $requests = [
        'frontpage' => 'Front',
        'profile' => 'Profile',
        'photo' => 'Photo',
        'compilation' => 'Compilation',
        'exhibition' => 'Compilation', // TODO check if it'll work
        'signIn' => 'SignIn',
        'signUp' => 'SignUp',
        '404' => '404' // move to smth like $requestsStatic
    ];

    private static $navbarLinks = [
        'frontpage' => 'feed',
        'profile' => 'profile',
        'signIn' => 'profile',
        'signUp' => 'profile',
        'exhibition' => 'selection'
    ];

    private static $staticPages = ['404', 'contacts', 'license'];

    private static function makeTitle(string $requestTitle): string
    {
        $titles = [
            'frontpage' => 'Выставка фотографов мехмата',
            'profile' => 'Платон Антониу',
            'photo' => 'Фото',
            'compilation' => 'Подборка &quot;Лето в Академгородке&quot;',
            'exhibition' => 'Текущая выставка',
            'signIn' => 'Вход',
            'signUp' => 'Регистрация',
            '404' => 'Страница не найдена'
        ];

        $title = $titles[$requestTitle]; // TODO add isset() just in case the page is not caught into 404

        if ($requestTitle !== 'frontpage') {
            $title = $title . ' | Выставка фотографов мехмата';
        }

        return $title;
    }

    public static function renderView(string $requestView)
    {
        $title = self::makeTitle($requestView);

        if (isset(self::$navbarLinks[$requestView])) {
            $currentNavbarLink = self::$navbarLinks[$requestView];
        } else {
            $currentNavbarLink = null;
        }

        if (isset(self::$requests[$requestView])) {
            $templateClass = self::$requests[$requestView];
            require 'html.php';
        }
    }
}
