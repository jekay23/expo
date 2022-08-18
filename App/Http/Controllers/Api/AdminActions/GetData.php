<?php

namespace Expo\App\Http\Controllers\Api\AdminActions;

use Exception;
use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\App\Models\Entities\Compilations;
use Expo\App\Models\Entities\Photos;
use Expo\App\Models\Entities\Users;
use Expo\Resources\Views\View;

class GetData
{
    /**
     * @throws Exception
     */
    public static function getUsers()
    {
        Authorizer::callIfUserIsEditor(function () {
            print json_encode(Users::getUsers());
        });
    }

    /**
     * @throws Exception
     */
    public static function getPhotos()
    {
        Authorizer::callIfUserIsEditor(function () {
            print json_encode(Photos::getPhotos('all'));
        });
    }

    /**
     * @throws Exception
     */
    public static function getCompilations()
    {
        Authorizer::callIfUserIsEditor(function () {
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
            Authorizer::callIfUserIsEditor(function ($uriQuery) {
                print json_encode(Photos::getCompilationItems($uriQuery['compilationID']));
            }, $uriQuery);
        } else {
            View::render('404');
        }
    }
}
