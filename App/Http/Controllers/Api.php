<?php

namespace Expo\App\Http\Controllers;

class Api
{
    private static $prefix = 'Api/';

    public static function execute(array $requestList, array $requestQuery)
    {
        if ('sign-in' == $requestList[0]) {
            require self::$prefix . 'signIn.php';
        } elseif ('sign-up' == $requestList[0]) {
            require self::$prefix . 'signUp.php';
        } elseif ('upload' == $requestList[0]) {
            require self::$prefix . 'upload.php';
        }
    }
}