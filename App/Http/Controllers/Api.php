<?php

namespace Expo\App\Http\Controllers;

use Exception;
use Expo\App\Http\Controllers\Api\AdminActions;
use Expo\App\Http\Controllers\Api\UserActions;
use Expo\Resources\Views\View;

class Api
{
    /**
     * @throws Exception
     */
    public static function execute(array $requestList, array $requestQuery)
    {
        $callbacks = [
            'sign-in' => function () {
                Authentication::authenticate('sign-in');
            },
            'sign-up' => function () {
                Authentication::authenticate('sign-up');
            },
            'upload' => function () {
                UserActions::upload();
            },
            'edit-profile' => function () {
                UserActions::editProfile();
            },
            'change-avatar' => function () {
                UserActions::changeAvatar();
            },
            'change-password-email' => function () {
                Authentication::changePasswordOrEmail();
            },
            'sign-out' => function () {
                Authentication::signOut();
            },
            'like' => function () {
                UserActions::toggleLike('like');
            },
            'dislike' => function () {
                UserActions::toggleLike('dislike');
            },
            'users' => function () {
                AdminActions::getUsers();
            },
            'photos' => function () {
                AdminActions::getPhotos();
            },
            'compilations' => function () {
                AdminActions::getCompilations();
            },
            'compilation-items' => function () {
                AdminActions::getCompilationItems();
            },
            'changeDesc' => function () {
                AdminActions::change('description');
            },
            'changeName' => function () {
                AdminActions::change('name');
            },
            'makeExhibit' => function () {
                AdminActions::change('isExhibit');
            },
            'hideCompilation' => function () {
                AdminActions::change('isHidden');
            },
            'hideProfile' => function () {
                AdminActions::change('isHiddenProfile');
            },
            'hideBio' => function () {
                AdminActions::change('isHiddenBio');
            },
            'hideAvatar' => function () {
                AdminActions::change('isHiddenAvatar');
            },
            'changeUserLevel' => function () {
                AdminActions::change('updateAccessLevel');
            },
            'hidePhoto' => function () {
                AdminActions::change('isPhotoHidden');
            },
            'createCompilation' => function () {
                AdminActions::createCompilation();
            },
            'addCompilationItem' => function () {
                AdminActions::addCompilationItem();
            },
            'removeCompilationItem' => function () {
                AdminActions::removeCompilationItem();
            },
            'checkEmail' => function () {
                AdminActions::sendEmail();
            },
            'verify' => function () {
                Authentication::verifyEmail();
            },
            'requestRestore' => function () {
                Authentication::requestRestore();
            },
            'restore' => function () {
                Authentication::restorePassword();
            },
            'quick-like' => function () {
                UserActions::quickAction('like');
            },
            'quick-dislike' => function () {
                UserActions::quickAction('dislike');
            }
        ];
        if (isset($callbacks[$requestList[0]])) {
            call_user_func($callbacks[$requestList[0]]);
        } else {
            View::render('404');
        }
    }

    public static function getUrlWithToken(string $type, string $token): string
    {
        return $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . "/$type?token=$token";
    }
}
