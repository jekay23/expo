<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Models\Entities\Users;
use Expo\Resources\Views\View;

class Profile
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        $userID = $requestList[0];
        $accessHidden = Authentication::checkUserIsEditor();
        try {
            $user = Users::getProfilePageData($userID, $accessHidden);
        } catch (Exception $e) {
            View::render('404');
            exit;
        }
        if ($userID == Authentication::getUserIdFromCookie()) {
            $user['isProfileOwner'] = true;
        } else {
            $user['isProfileOwner'] = false;
        }
        if (isset($requestList[1])) {
            if ($user['isProfileOwner']) {
                switch ($requestList[1]) {
                    case 'edit':
                        View::render('editProfile', $user);
                        break;
                    case 'change-avatar':
                        View::render('changeAvatar', $user);
                        break;
                    case 'change-password-email':
                        View::render('changePasswordEmail', $user);
                        break;
                    default:
                        View::render('404');
                        break;
                }
            } else {
                View::render('403');
            }
        } else {
            View::render('profile', $user, $user['name']);
        }
    }
}
