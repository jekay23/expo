<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Models\Compilations;
use Expo\Resources\Views\View;

class Exhibition
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        if (!empty($requestList)) {
            View::render('404');
        } else {
            list($status, $compilationID) = Compilations::getCurrentExhibition();
            if ($status) {
                Compilation::prepare([$compilationID], $requestQuery);
            } else {
                View::render('503');
            }
        }
    }
}
