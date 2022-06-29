<?php

/**
 * View class
 * In the future it will construct the whole page, but now it's quite primitive
 */

namespace Expo\Resources\Views;

class View
{
    private static $requests = array(
        'frontpage' => 'front.php',
        'profile' => 'profile.php',
        'photo' => 'photo.php',
        'compilation' => 'compilation.php',
        'exhibition' => 'exhibition.php',
        'signIn' => 'signIn.php',
        'signUp' => 'signUp.php',
        '404' => '404.php'
    );

    private static function makeTitle(string $requestTitle): string
    {
        $titles = array(
            'frontpage' => 'Выставка фотографов мехмата',
            'profile' => 'Платон Антониу',
            'photo' => 'Фото',
            'compilation' => 'Подборка &quot;Лето в Академгородке&quot;',
            'exhibition' => 'Текущая выставка',
            'signIn' => 'Вход',
            'signUp' => 'Регистрация',
            '404' => 'Страница не найдена'
        );

        $title = $titles[$requestTitle]; // TODO add isset() just in case the page is not caught into 404

        if ($requestTitle !== 'frontpage') {
            $title = $title . ' | Выставка фотографов мехмата';
        }

        return $title;
    }

    public static function renderView(string $requestView)
    {
        $title = self::makeTitle($requestView);

        if (isset(self::$requests[$requestView])) {
            $template = 'Pages/' . self::$requests[$requestView];
            require 'html.php';
        }
    }
}
