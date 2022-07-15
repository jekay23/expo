<?php

namespace Expo\App\Http\Controllers;

use Expo\App\Http\Controllers\Api\Authentication;

class Api
{
    private static $prefix = 'Api/';

    public static function execute(array $requestList, array $requestQuery)
    {
        switch ($requestList[0]) {
            case 'sign-in':
                Authentication::authenticate('sign-in');
                break;
            case 'sign-up':
                Authentication::authenticate('sign-up');
                break;
            case 'upload':
                require self::$prefix . 'upload.php';
                break;
            case 'edit-profile':
                require self::$prefix . 'editProfile.php';
                break;
            case 'change-avatar':
                require self::$prefix . 'changeAvatar.php';
                break;
            case 'change-password':
                require self::$prefix . 'changePassword.php';
                break;
            case 'sign-out':
                Authentication::signOut();
                break;
        }
    }
}
