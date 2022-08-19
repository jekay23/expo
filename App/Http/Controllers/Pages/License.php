<?php

namespace Expo\App\Http\Controllers\Pages;

class License
{
    public static function prepare(array $requestList)
    {
        PagesWithNoPreparation::open($requestList, 'license');
    }
}
