<?php

namespace Expo\App\Http\Controllers\Api;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Http\Controllers\HTTPQueryHandler;
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
        self::callIfUserIsEditor(function () {
            print json_encode(Users::getUsers());
        });
    }

    /**
     * @throws Exception
     */
    public static function getPhotos()
    {
        self::callIfUserIsEditor(function () {
            print json_encode(Photos::getPhotos('all'));
        });
    }

    /**
     * @throws Exception
     */
    public static function getCompilations()
    {
        self::callIfUserIsEditor(function () {
            print json_encode(Compilations::getCompilations());
        });
    }

    /**
     * @throws Exception
     */
    public static function getCompilationItems()
    {
        $uriQuery = HTTPQueryHandler::validateAndParseGet();
        if (isset($uriQuery['compilationID'])) {
            self::callIfUserIsEditor(function ($uriQuery) {
                print json_encode(Photos::getCompilationItems($uriQuery['compilationID']));
            }, $uriQuery);
        } else {
            View::render('404');
        }
    }

    /**
     * @throws Exception
     */
    public static function change(string $field)
    {
        $callbacks = [
            'description' => function ($uriQuery) {
                Compilations::updateString(
                    $uriQuery['compilationID'],
                    'description',
                    $uriQuery['value']
                );
            },
            'name' => function ($uriQuery) {
                Compilations::updateString(
                    $uriQuery['compilationID'],
                    'name',
                    $uriQuery['value']
                );
            },
            'isHidden' => function ($uriQuery) {
                Compilations::updateBool(
                    $uriQuery['compilationID'],
                    'isHidden',
                    self::convertToBool($uriQuery['enabled'])
                );
            },
            'isExhibit' => function ($uriQuery) {
                Compilations::updateExhibit(
                    $uriQuery['compilationID'],
                    'isExhibit',
                    self::convertToBool($uriQuery['enabled']),
                    ($uriQuery['value'] ? Compilations::getNextExhibitNumber() : 0)
                );
            },
            'isHiddenProfile' => function ($uriQuery) {
                Users::updateBool(
                    $uriQuery['userID'],
                    'isHiddenProfile',
                    self::convertToBool($uriQuery['enabled'])
                );
            },
            'isHiddenBio' => function ($uriQuery) {
                Users::updateBool(
                    $uriQuery['userID'],
                    'isHiddenBio',
                    self::convertToBool($uriQuery['enabled'])
                );
            },
            'isHiddenAvatar' => function ($uriQuery) {
                Users::updateBool(
                    $uriQuery['userID'],
                    'isHiddenAvatar',
                    self::convertToBool($uriQuery['enabled'])
                );
            },
            'updateAccessLevel' => function ($uriQuery) {
                Users::updateAccessLevel(
                    $uriQuery['userID'],
                    $uriQuery['value']
                );
            },
            'isPhotoHidden' => function ($uriQuery) {
                Photos::hide(
                    $uriQuery['photoID'],
                    self::convertToBool($uriQuery['enabled'])
                );
            }
        ];
        if (Authentication::checkUserIsEditor()) {
            $uriQuery = HTTPQueryHandler::validateAndParseGet();
            if (isset($callbacks[$field])) {
                call_user_func($callbacks[$field], $uriQuery);
            } else {
                View::render('404');
            }
        } else {
            View::render('403');
        }
    }

    public static function createCompilation()
    {
        self::callIfUserIsEditor(function () {
            Compilations::create(Authentication::getUserIdFromCookie());
        });
    }

    public static function addCompilationItem()
    {
        $uriQuery = HTTPQueryHandler::validateAndParseGet();
        if (isset($uriQuery['compilationID']) && isset($uriQuery['photoID'])) {
            self::callIfUserIsEditor(function ($uriQuery) {
                Compilations::addCompilationItem($uriQuery['compilationID'], $uriQuery['photoID']);
            }, $uriQuery);
        } else {
            View::render('404');
        }
    }

    public static function removeCompilationItem()
    {
        $uriQuery = HTTPQueryHandler::validateAndParseGet();
        if (isset($uriQuery['compilationID']) && isset($uriQuery['photoID'])) {
            self::callIfUserIsEditor(function ($uriQuery) {
                Compilations::removeCompilationItem($uriQuery['compilationID'], $uriQuery['photoID']);
            }, $uriQuery);
        } else {
            View::render('404');
        }
    }

    public static function sendEmail()
    {
        $uriQuery = HTTPQueryHandler::validateAndParseGet();
        if (isset($uriQuery['type']) && isset($uriQuery['userID'])) {
            EmailSender::send($uriQuery['type'], $uriQuery['userID'], ['This is a test email']);
        } else {
            View::render('404');
        }
    }

    private static function convertToBool($value): bool
    {
        return ('true' === $value || 1 === $value || '1' === $value);
    }

    private static function callIfUserIsEditor(callable $callback, $uriQuery = null)
    {
        try {
            if (Authentication::checkUserIsEditor()) {
                call_user_func($callback, $uriQuery);
            } else {
                View::render('403');
            }
        } catch (Exception $e) {
            View::render('503');
        }
    }
}
