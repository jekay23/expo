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
        'exhibition' => 'Compilation',
        'signIn' => 'SignIn',
        'signUp' => 'SignUp'
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

    public static function render(string $requestView)
    {
        $title = self::makeTitle($requestView);

        $currentNavbarLink = self::$navbarLinks[$requestView] ?? null;

        if (isset(self::$requests[$requestView])) {
            $templateClass = self::$requests[$requestView];
            Html::requireDynamic($title, $templateClass, $currentNavbarLink);
        } elseif (in_array($requestView, self::$staticPages)) {
            $page = $requestView;
            Html::requireStatic($title, $page);
        }
    }

    public static function requireTemplate(string $templateName, string $templateType = '', array $variables = null)
    {
        if ('' !== $templateType) {
            $templateName = $templateType . 's/Templates/' . $templateName;
        }
        if (isset($variables)) {
            extract($variables);
        }
        $templateName .= '.php';
        require $templateName;
    }
}
