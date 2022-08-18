<?php

namespace Expo\App\Http\Controllers\Api\AdminActions;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\App\Models\Entities\Compilations;
use Expo\App\Models\Entities\Photos;
use Expo\App\Models\Entities\Users;
use Expo\Resources\Views\View;

class ChangeData
{
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
        Authorizer::callIfUserIsEditor(function () {
            Compilations::create(Authentication::getUserIdFromCookie());
        });
    }

    public static function addCompilationItem()
    {
        $uriQuery = HTTPQueryHandler::validateAndParseGet();
        if (isset($uriQuery['compilationID']) && isset($uriQuery['photoID'])) {
            Authorizer::callIfUserIsEditor(function ($uriQuery) {
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
            Authorizer::callIfUserIsEditor(function ($uriQuery) {
                Compilations::removeCompilationItem($uriQuery['compilationID'], $uriQuery['photoID']);
            }, $uriQuery);
        } else {
            View::render('404');
        }
    }

    private static function convertToBool($value): bool
    {
        return ('true' === $value || 1 === $value || '1' === $value);
    }
}
