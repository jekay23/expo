<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Http\Controllers\Api\Authentication;
use Expo\App\Models\QueryBuilder;
use Expo\Resources\Views\View;

class Profile
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        $userID = $requestList[0];
        list($status, $user) = QueryBuilder::getProfileData($userID);
        if ($status) {
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
        } else {
            View::render('404');
        }
    }
}
