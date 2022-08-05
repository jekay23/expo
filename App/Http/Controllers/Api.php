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
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['compilationID'])) {
                    AdminActions::getCompilationItems($uriQuery['compilationID']);
                } else {
                    View::render('404');
                }
                break;
            case 'changeDesc':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['compilationID']) && isset($uriQuery['value'])) {
                    AdminActions::change($uriQuery['compilationID'], 'description', $uriQuery['value']);
                } else {
                    View::render('404');
                }
                break;
            case 'changeName':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['compilationID']) && isset($uriQuery['value'])) {
                    AdminActions::change($uriQuery['compilationID'], 'name', $uriQuery['value']);
                } else {
                    View::render('404');
                }
                break;
            case 'makeExhibit':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['compilationID']) && isset($uriQuery['enabled'])) {
                    AdminActions::change($uriQuery['compilationID'], 'isExhibit', $uriQuery['enabled']);
                } else {
                    View::render('404');
                }
                break;
            case 'hideCompilation':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['compilationID']) && isset($uriQuery['enabled'])) {
                    AdminActions::change($uriQuery['compilationID'], 'isHidden', $uriQuery['enabled']);
                } else {
                    View::render('404');
                }
                break;
            case 'hideProfile':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['userID']) && isset($uriQuery['enabled'])) {
                    AdminActions::change($uriQuery['userID'], 'isHiddenProfile', $uriQuery['enabled']);
                } else {
                    View::render('404');
                }
                break;
            case 'hideBio':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['userID']) && isset($uriQuery['enabled'])) {
                    AdminActions::change($uriQuery['userID'], 'isHiddenBio', $uriQuery['enabled']);
                } else {
                    View::render('404');
                }
                break;
            case 'hideAvatar':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['userID']) && isset($uriQuery['enabled'])) {
                    AdminActions::change($uriQuery['userID'], 'isHiddenAvatar', $uriQuery['enabled']);
                } else {
                    View::render('404');
                }
                break;
            case 'changeUserLevel':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['userID']) && isset($uriQuery['value'])) {
                    AdminActions::change($uriQuery['userID'], 'changeAccessLevel', $uriQuery['value']);
                } else {
                    View::render('404');
                }
                break;
            case 'hidePhoto':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['photoID']) && isset($uriQuery['enabled'])) {
                    AdminActions::change($uriQuery['photoID'], 'hidePhoto', $uriQuery['enabled']);
                } else {
                    View::render('404');
                }
                break;
            case 'createCompilation':
                AdminActions::createCompilation();
                break;
            case 'addCompilationItem':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['compilationID']) && isset($uriQuery['photoID'])) {
                    AdminActions::addCompilationItem($uriQuery['compilationID'], $uriQuery['photoID']);
                } else {
                    View::render('404');
                }
                break;
            case 'removeCompilationItem':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['compilationID']) && isset($uriQuery['photoID'])) {
                    AdminActions::removeCompilationItem($uriQuery['compilationID'], $uriQuery['photoID']);
                } else {
                    View::render('404');
                }
                break;
            case 'checkEmail':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['userID']) && isset($uriQuery['type'])) {
                    AdminActions::sendEmail($uriQuery['userID'], $uriQuery['type']);
                } else {
                    View::render('404');
                }
                break;
            case 'verify':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['token'])) {
                    Authentication::verifyEmail($uriQuery['token']);
                } else {
                    View::render('404');
                }
                break;
            case 'requestRestore':
                Authentication::requestRestore();
                break;
            case 'restore':
                $uriQuery = self::getUriQueryArray();
                if (isset($uriQuery['token'])) {
                    Authentication::restorePassword($uriQuery['token']);
                } else {
                    View::render('404');
                }
                break;
        }
    }

    private static function getUriQueryArray(): array
    {
        $uriQuery = [];
        parse_str($_SERVER['QUERY_STRING'], $uriQuery);
        return $uriQuery;
    }

    public static function getUrlWithToken(string $type, string $token): string
    {
        $url = $_SERVER['REQUEST_SCHEME'] . '://' . $_SERVER['SERVER_NAME'] . "/$type?token=$token";
        return $url;
    }
}
