<?php

namespace Expo\App\Http\Controllers\Pages;

use Exception;
use Expo\App\Models\Entities\Compilations;
use Expo\Resources\Views\Html;

class Exhibition
{
    /**
     * @throws Exception
     */
    public static function prepare(array $requestList)
    {
        if (!empty($requestList)) {
            Html::render('404');
        } else {
            $compilationID = Compilations::getCurrentExhibitionId();
            if ($compilationID) {
                Compilation::prepare([$compilationID]);
            } else {
                Html::render('503');
            }
        }
    }
}
