<?php

namespace Expo\App\Http\Controllers\Pages;

class FAQ
{
    public static function prepare(array $requestList)
    {
        PagesWithNoPreparation::open($requestList, 'faq');
    }
}
