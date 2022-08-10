<?php

namespace Expo\App\Http\Controllers\Api;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Mail\EmailSender;
use Expo\App\Models\Entities\Compilations;
use Expo\App\Models\Entities\Photos;
use Expo\App\Models\Entities\Users;
use Expo\Resources\Views\View;

class AdminActions
{
    /**
     * @throws Exception
     */
    public static function getUsers()
    {
        if (Authentication::checkUserIsEditor()) {
            print json_encode(Users::getUsers());
        } else {
            View::render('403');
        }
    }

    /**
     * @throws Exception
     */
    public static function getPhotos()
    {
        if (Authentication::checkUserIsEditor()) {
            print json_encode(Photos::getPhotos('all'));
        } else {
            View::render('403');
        }
    }

    /**
     * @throws Exception
     */
    public static function getCompilations()
    {
        if (Authentication::checkUserIsEditor()) {
            print json_encode(Compilations::getCompilations());
        } else {
            View::render('403');
        }
    }

    /**
     * @throws Exception
     */
    public static function getCompilationItems(int $compilationID)
    {
        if (Authentication::checkUserIsEditor()) {
            print json_encode(Photos::getCompilationItems($compilationID));
        } else {
            View::render('403');
        }
    }

    public static function change(int $id, string $field, $value)
    {
        if (Authentication::checkUserIsEditor()) {
            if ('description' == $field || 'name' == $field) {
                Compilations::updateString($id, $field, $value);
            } elseif ('isHidden' == $field) {
                if ('true' === $value || 1 === $value || '1' === $value) {
                    $value = true;
                } else {
                    $value = false;
                }
                Compilations::updateBool($id, $field, $value);
            } elseif ('isExhibit' == $field) {
                if ('true' === $value || 1 === $value || '1' === $value) {
                    $value = true;
                    $exhibitNumber = Compilations::getNextExhibitNumber();
                } else {
                    $value = false;
                    $exhibitNumber = 0;
                }
                Compilations::updateExhibit($id, $field, $value, $exhibitNumber);
            } elseif ('isHiddenProfile' == $field || 'isHiddenBio' == $field || 'isHiddenAvatar' == $field) {
                if ('true' === $value || 1 === $value || '1' === $value) {
                    $value = true;
                } else {
                    $value = false;
                }
                Users::updateBool($id, $field, $value);
            } elseif ('updateAccessLevel' == $field) {
                Users::updateAccessLevel($id, $value);
            } elseif ('hidePhoto' == $field) {
                if ('true' === $value || 1 === $value || '1' === $value) {
                    $value = true;
                } else {
                    $value = false;
                }
                Photos::hide($id, $value);
            } else {
                View::render('404');
            }
        } else {
            View::render('403');
        }
    }

    public static function createCompilation()
    {
        if (Authentication::checkUserIsEditor()) {
            Compilations::create(Authentication::getUserIdFromCookie());
        } else {
            View::render('403');
        }
    }

    public static function addCompilationItem(int $compilationID, int $photoID)
    {
        if (Authentication::checkUserIsEditor()) {
            Compilations::addCompilationItem($compilationID, $photoID);
        } else {
            View::render('403');
        }
    }

    public static function removeCompilationItem($compilationID, $photoID)
    {
        if (Authentication::checkUserIsEditor()) {
            Compilations::removeCompilationItem($compilationID, $photoID);
        } else {
            View::render('403');
        }
    }

    public static function sendEmail(int $id, string $type)
    {
        EmailSender::send($type, $id);
    }
}
