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
                if ('edit' == $requestList[1] && $user['isProfileOwner'] = true) {
                    View::render('editProfile', $user);
                } elseif ('change-avatar' == $requestList[1] && $user['isProfileOwner'] = true) {
                    View::render('changeAvatar', $user);
                } elseif ('change-password' == $requestList[1] && $user['isProfileOwner'] = true) {
                    View::render('changePassword', $user);
                } else {
                    View::render('404');
                }
            } else {
                View::render('profile', $user, $user['name']);
            }
        } else {
            View::render('404');
        }
    }
}
