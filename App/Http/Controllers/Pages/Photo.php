<?php

namespace Expo\App\Http\Controllers\Pages;

use Exception;
use Expo\App\Models\Entities\Photos;
use Expo\Resources\Views\Html;

class Photo
{
    /**
     * @throws Exception
     */
    public static function prepare(array $requestList)
    {
        $photoID = $requestList[0];
        $photo = Photos::getPhotoDetails($photoID);
        if (empty($photo)) {
            Html::render('404');
        } else {
            Html::render('photo', $photo, 'Фото пользователя ' . $photo['authorName']);
        }
    }
}
