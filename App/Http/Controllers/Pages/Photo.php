<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Models\QueryBuilder;
use Expo\Resources\Views\View;

class Photo
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        $photoID = $requestList[0];

        list($status, $photo) = QueryBuilder::getPhotoDetails($photoID);
        if (!$status) {
            View::render('404');
        } else {
            View::render('photo', $photo, 'Фото пользователя ' . $photo['authorName']);
        }
    }
}
