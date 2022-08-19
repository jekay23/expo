<?php

namespace Expo\App\Http\Controllers\Pages;

class Front
{
    public static function prepare(array $requestList)
    {
        PagesWithNoPreparation::open($requestList, 'front');
    }
}
