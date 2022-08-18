<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\Resources\Views\View;

class Restore
{

    public static function prepare(array $requestList)
    {
        $uriQuery = HTTPQueryHandler::validateAndParseGet();
        if (isset($uriQuery['token']) && empty($requestList)) {
            $token = $uriQuery['token'];
            View::render('restore', compact('token'));
        } else {
            View::render('404');
        }
    }
}
