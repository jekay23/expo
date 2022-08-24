<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\Html;

class Error
{
    private static array $types = ['403', '404', '503'];

    public static function prepare(string $type)
    {
        if (!in_array($type, self::$types)) {
            $type = '404';
        }
        Html::render($type);
    }
}
