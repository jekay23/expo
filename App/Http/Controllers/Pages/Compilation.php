<?php

namespace Expo\App\Http\Controllers\Pages;

use Exception;
use Expo\App\Http\Controllers\Components\PhotoDisplay;
use Expo\App\Models\Entities\Compilations;
use Expo\App\Models\Entities\Photos;
use Expo\Resources\Views\Html;

class Compilation
{
    /**
     * @throws Exception
     */
    public static function prepare(array $requestList)
    {
        if (empty($requestList)) {
            Html::render('404');
        } else {
            $compilationID = $requestList[0];
            $compilation = Compilations::getCompilationDetails($compilationID);
            if (empty($compilation)) {
                Html::render('404');
            } else {
                $compilationPhotos = PhotoDisplay::generatePhotosArray(Photos::getPhotos(
                    'compilation',
                    30,
                    ['compilationID' => $compilationID]
                ));
                if (empty($compilationPhotos)) {
                    Html::render('404');
                } else {
                    $compilation['photos'] = $compilationPhotos;
                    Html::render('compilation', $compilation);
                }
            }
        }
    }
}
