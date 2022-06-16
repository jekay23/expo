<?php

namespace Expo\App\Http\Controllers;

class Images
{
    public static function showFile($requestList, $query)
    {
        if (isset($requestList[0]) && empty($requestList[1])) {
            $path = __DIR__ . '/../../../Public/Images/';
            if (file_exists($path  . $requestList[0])) {
                require $path . $requestList[0];
            } else {
                require $path . 'noImage.svg';
            }
        }
    }
}
