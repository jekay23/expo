<?php

namespace Expo\App\Http\Controllers\Api;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Models\Compilations;
use Expo\App\Models\Photos;
use Expo\App\Models\Users;
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
}
