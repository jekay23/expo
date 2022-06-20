<?php

/**
 * View class
 * In the future it will construct the whole page, but now it's quite primitive
 */

namespace Expo\Resources\Views;

class View
{
    private static $requests = array(
        'frontpage' => 'frontPageTemplate.php',
        'profile' => 'profilePageTemplate.php',
        'photo' => 'photoPageTemplate.php',
        'compilation' => 'compilationPageTemplate.php',
        'exhibition' => 'exhibitionPageTemplate.php',
        'signIn' => 'signInPageTemplate.php',
        'signUp' => 'signUpPageTemplate.php',
        '404' => '404Template.php'
    );

    private static function makeTitle($requestTitle): string
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

    public static function showView($requestView)
    {
        $title = self::makeTitle($requestView);

        if (isset(self::$requests[$requestView])) {
            $template = self::$requests[$requestView];
            require 'mainTemplate.php';
        }
    }

    public static function renderHeader2($headerText)
    {
        echo '<h2 class="pt-3 pb-1 ps-md-5">' . $headerText . '</h2>';
    }
}
