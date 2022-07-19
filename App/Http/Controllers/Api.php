<?php

namespace Expo\App\Http\Controllers;

use Expo\App\Http\Controllers\Api\UserActions;
use Expo\App\Http\Controllers\Authentication;

class Api
{
    public static function execute(array $requestList, array $requestQuery)
    {
        switch ($requestList[0]) {
            case 'sign-in':
                Authentication::authenticate('sign-in');
                break;
            case 'sign-up':
                Authentication::authenticate('sign-up');
                break;
            case 'upload':
                UserActions::upload();
                break;
            case 'edit-profile':
                UserActions::editProfile();
                break;
            case 'change-avatar':
                UserActions::changeAvatar();
                break;
            case 'change-password-email':
                Authentication::changePasswordEmail();
                break;
            case 'sign-out':
                Authentication::signOut();
                break;
            case 'like':
                UserActions::addLike();
                break;
            case 'dislike':
                UserActions::removeLike();
                break;
        }
    }
}
