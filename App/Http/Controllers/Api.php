<?php

namespace Expo\App\Http\Controllers;

class Api
{
    private static $prefix = 'Api/';

    public static function execute(array $requestList, array $requestQuery)
    {
        if ('sign-in.php' === $requestList[0]) {
            require self::$prefix . 'signIn.php';
        } elseif ('sign-up.php' === $requestList[0]) {
            require self::$prefix . 'signUp.php';
        }
    }
}