<?php

namespace Expo\App\Http\Controllers;

class Api
{
    private static $prefix = 'Api/';

    public static function execute(array $requestList, array $requestQuery)
    {
        switch ($requestList[0]) {
            case 'sign-in':
                require self::$prefix . 'signIn.php';
                break;
            case 'sign-up':
                require self::$prefix . 'signUp.php';
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
        }
    }
}
