<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\Resources\Views\Html;

class Verify
{
    public static function prepare(array $requestList)
    {
        $uriQuery = HTTPQueryHandler::validateAndParseGet();
        if (isset($uriQuery['token']) && empty($requestList)) {
            $token = $uriQuery['token'];
            Html::render('verify', compact('token'));
        } else {
            Html::render('404');
        }
    }
}
