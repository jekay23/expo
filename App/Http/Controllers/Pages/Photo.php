<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Models\Entities\Photos;
use Expo\Resources\Views\View;

class Photo
{
    /**
     * @throws \Exception
     */
    public static function prepare(array $requestList)
    {
        $photoID = $requestList[0];
        $photo = Photos::getPhotoDetails($photoID);
        if (empty($photo)) {
            View::render('404');
        } else {
            View::render('photo', $photo, 'Фото пользователя ' . $photo['authorName']);
        }
    }
}
