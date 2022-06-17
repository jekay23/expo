<?php

namespace Expo\App\Http\Controllers;

class Css
{
    public static function showFile($requestList, $query)
    {
        if (isset($requestList[0]) && empty($requestList[1])) {
            $path = __DIR__ . '/../../../Public/Css/';
            if (file_exists($path  . $requestList[0])) {
                require $path . $requestList[0];
            }
        }
    }
}
