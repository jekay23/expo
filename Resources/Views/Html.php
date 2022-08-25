<?php

namespace Expo\Resources\Views;

use Expo\App\Http\Controllers\Authentication;
use Expo\Resources\Views\Components\Title;

class Html
{
    private static array $dynamicPageClasses = [
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
        $stickFooter = false;
        $userID = Authentication::getUserIdFromCookie();
        $page = null;
        $templateClass = null;
        if (isset(self::$dynamicPageClasses[$requestView])) {
            $templateClass = 'Expo\\Resources\\Views\\Pages\\' . self::$dynamicPageClasses[$requestView];
        } elseif (in_array($requestView, self::$staticPages)) {
            $page = 'Pages/Templates/' . $requestView . '.php';
        }
        $renderMainCallback = self::getRenderMainCallback($templateClass, $page, $stickFooter, $data);
        $compact = compact('title', 'userID', 'requestView', 'stickFooter', 'renderMainCallback');
        View::requireTemplate('html', '', $compact);
    }

    public static function getRenderMainCallback($templateClass, $page, bool &$stickFooter, $data)
    {
        if (isset($templateClass)) {
            return function () use ($templateClass, $data, $stickFooter) {
                $templateClass::render($stickFooter, $data);
            };
        } elseif (isset($page)) {
            return function () use ($page) {
                require $page;
            };
        }
    }
}
