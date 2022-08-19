<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class RequestRestore
{
    public static function prepare(array $requestList)
    {
        PagesWithNoPreparation::open($requestList, 'requestRestore');
    }
}
