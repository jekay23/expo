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
    public static function assemble(array $requestList, array $requestQuery)
    {
        $userID = $requestList[0];
        list($status, $user) = QueryBuilder::getProfileData($userID);
        if ($userID == Authentication::getUserIdFromCookie()) {
            $user['isProfileOwner'] = true;
        } else {
            $user['isProfileOwner'] = false;
        }
        if ($status) {
            View::render('profile', $user);
        } else {
            View::render('404');
        }
    }
}
