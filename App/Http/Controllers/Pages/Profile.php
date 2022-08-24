<?php

namespace Expo\App\Http\Controllers\Pages;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Http\Controllers\Components\PhotoDisplay;
use Expo\App\Models\Entities\Users;
use Expo\Resources\Views\Html;

class Profile
{
    /**
     * @throws Exception
     */
    public static function prepare(array $requestList)
    {
        $userID = $requestList[0];
        $accessHidden = Authentication::checkUserIsEditor();
        try {
            $user = Users::getProfilePageData($userID, $accessHidden);
        } catch (Exception $e) {
            Html::render('404');
            exit;
        }
        $user['photos'] = PhotoDisplay::generatePhotosArray($user['photos']);
        if ($userID == Authentication::getUserIdFromCookie()) {
            $user['isProfileOwner'] = true;
        } else {
            $user['isProfileOwner'] = false;
        }
        if (isset($requestList[1])) {
            if ($user['isProfileOwner']) {
                switch ($requestList[1]) {
                    case 'edit':
                        Html::render('editProfile', $user);
                        break;
                    case 'change-avatar':
                        Html::render('changeAvatar', $user);
                        break;
                    case 'change-password-email':
                        Html::render('changePasswordEmail', $user);
                        break;
                    default:
                        Html::render('404');
                        break;
                }
            } else {
                Html::render('403');
            }
        } else {
            Html::render('profile', $user, $user['name']);
        }
    }
}
