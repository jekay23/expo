<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Models\Entities\Compilations;
use Expo\Resources\Views\View;

class Exhibition
{
    public static function prepare(array $requestList)
    {
        if (!empty($requestList)) {
            View::render('404');
        } else {
            $compilationID = Compilations::getCurrentExhibitionId();
            if ($compilationID) {
                Compilation::prepare([$compilationID]);
            } else {
                View::render('503');
            }
        }
    }
}
