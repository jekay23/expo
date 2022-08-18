<?php

namespace Expo\App\Http\Controllers\Api\AdminActions;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\Resources\Views\View;

class Authorizer
{
    public static function callIfUserIsEditor(callable $callback, $uriQuery = null)
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