<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Models\QueryBuilder as QB;
use Expo\Resources\Views\View;

class Compilation
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        $compilationID = $requestList[0];
        list($status, $compilation) = QB::getCompilationDetails($compilationID);
        if (!$status) {
            View::render('404');
        }
        list($status, $compilationPhotos) = QB::getPhotos('compilation', 30, ['compilationID' => $compilationID]);
        if (!$status) {
            View::render('404');
        }
        $compilation['photos'] = $compilationPhotos;
        View::render('compilation', $compilation);
    }
}
