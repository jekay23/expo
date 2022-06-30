<?php

/**
 * View class
 * In the future it will construct the whole page, but now it's quite primitive
 */

namespace Expo\Resources\Views;

class View
{
    private static $requests = [
        'frontpage' => 'Front',
        'profile' => 'Profile',
        'photo' => 'Photo',
        'compilation' => 'Compilation',
        'exhibition' => 'Exhibition',
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

        $currentNavbarLink = self::$navbarLinks[$requestView];

        if (isset(self::$requests[$requestView])) {
            $template = 'Pages/' . self::$requests[$requestView];
            require 'html.php';
        }
    }
}
