<?php

namespace Expo\Routes;

use Expo\Resources\Views\View;

class ApiRouter extends Router
{
    protected static array $callbacks = [];

    public static function callback(array $requestList)
    {
        if (isset($requestList[0]) && !isset($requestList[1])) {
            if (isset(static::$callbacks[$requestList[0]])) {
                return call_user_func(static::$callbacks[$requestList[0]]);
            }
        }
        return call_user_func(function () {
            View::render('404');
        });
    }
}
