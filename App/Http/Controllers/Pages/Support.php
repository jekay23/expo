<?php

namespace Expo\App\Http\Controllers\Pages;

class Support
{
    public static function prepare(array $requestList)
    {
        PagesWithNoPreparation::open($requestList, 'support');
    }
}
