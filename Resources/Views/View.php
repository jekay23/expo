<?php

namespace Expo\Resources\Views;

use Expo\App\Http\Controllers\Authentication;

class View
{
    private static array $requests = [
        'front' => 'Front',
        'profile' => 'Profile',
        'photo' => 'Photo',
        'compilation' => 'Compilation',
        'exhibition' => 'Compilation',
        'editProfile' => 'EditProfile',
        'changePasswordEmail' => 'ChangePasswordEmail',
        'verify' => 'Verify',
        'restore' => 'Restore'
    ];

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

    private static array $staticPages = [
        '404',
        '403',
        '503',
        'upload',
        'signIn',
        'signUp',
        'support',
        'license',
        'faq',
        'changeAvatar',
        'requestRestore'
    ];

    public static function render(string $requestView, array $data = null, string $title = '')
    {
        $title = Title::get($requestView, $title);

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
