<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\Resources\Views\View;

class Error404
{
    public static function prepare()
    {
        View::render('404');
    }
}
