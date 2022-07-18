<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Models\QueryBuilder;
use Expo\Resources\Views\View;

class Exhibition
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        list($status, $compilationID) = QueryBuilder::getCurrentExhibition();
        if ($status) {
            Compilation::prepare([$compilationID], []);
        } else {
            View::render('503');
        }
    }
}
