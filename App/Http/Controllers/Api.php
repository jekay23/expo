<?php

namespace Expo\App\Http\Controllers;

use Exception;
use Expo\App\Http\Controllers\Api\AdminActions;
use Expo\App\Http\Controllers\Api\UserActions;
use Expo\App\Http\Controllers\Authentication;
use Expo\Resources\Views\View;

class Api
{
    /**
     * @throws Exception
     */
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
            case 'users':
                AdminActions::getUsers();
                break;
            case 'photos':
                AdminActions::getPhotos();
                break;
            case 'compilations':
                AdminActions::getCompilations();
                break;
            case 'compilation-items':
                $uriQuery = [];
                parse_str($_SERVER['QUERY_STRING'], $uriQuery);
                if (isset($uriQuery['compilationID'])) {
                    $compilationID = $uriQuery['compilationID'];
                    AdminActions::getCompilationItems($compilationID);
                } else {
                    View::render('404');
                }
                break;
        }
    }
}
