<?php

namespace Expo\Resources\Views;

use Expo\App\Http\Controllers\Authentication;
use Expo\Resources\Views\Pages;

class View
{
    private static array $requests = [
        'frontpage' => 'Front',
        'profile' => 'Profile',
        'photo' => 'Photo',
        'compilation' => 'Compilation',
        'exhibition' => 'Compilation',
        'editProfile' => 'EditProfile',
        'changePasswordEmail' => 'ChangePasswordEmail'
    ];

    private static array $navbarLinks = [
        'frontpage' => 'feed',
        'profile' => 'profile',
        'signIn' => 'profile',
        'signUp' => 'profile',
        'editProfile' => 'profile',
        'changeAvatar' => 'profile',
        'changePasswordEmail' => 'profile',
        'compilation' => 'selection'
    ];

    private static array $staticPages = ['404', '403', '503', 'upload', 'signIn', 'signUp',
                                         'contacts', 'license', 'changeAvatar'];

    private static array $titles = [
        'frontpage' => 'Выставка фотографов мехмата',
        'profile' => 'Платон Антониу',
        'photo' => 'Фото',
        'compilation' => 'Подборка &quot;Лето в Академгородке&quot;',
        'exhibition' => 'Текущая выставка',
        'signIn' => 'Вход',
        'signUp' => 'Регистрация',
        '404' => 'Страница не найдена',
        '403' => 'Доступ запрещён',
        '503' => 'Сервис не отвечает',
        'upload' => 'Загрузка снимков',
        'editProfile' => 'Настройки профиля',
        'changeAvatar' => 'Смена аватара',
        'changePasswordEmail' => 'Смена пароля и email'
    ];

    private static function makeTitle(string $requestTitle, $override = null): string
    {
        $title = $override ?? self::$titles[$requestTitle];

        if ($requestTitle != 'frontpage') {
            $title = $title . ' | Выставка фотографов мехмата';
        }

        return $title;
    }

    public static function render(string $requestView, array $data = null, string $title = null)
    {
        $title = self::makeTitle($requestView, $title);

        $userID = Authentication::getUserIdFromCookie();

        $currentNavbarLink = self::$navbarLinks[$requestView] ?? null;

        if (isset(self::$requests[$requestView])) {
            $templateClass = self::$requests[$requestView];
            Html::requireDynamic($title, $templateClass, $data, $userID, $currentNavbarLink);
        } elseif (in_array($requestView, self::$staticPages)) {
            $page = $requestView;
            Html::requireStatic($title, $page, $userID);
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
