<?php

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Models\Entities\Compilations;
use Expo\App\Models\Entities\Photos;
use Expo\Resources\Views\View;

class Compilation
{
    public static function prepare(array $requestList)
    {
        if (empty($requestList)) {
            View::render('404');
        } else {
            $compilationID = $requestList[0];
            $compilation = Compilations::getCompilationDetails($compilationID);
            if (empty($compilation)) {
                View::render('404');
            } else {
                $compilationPhotos = Photos::getPhotos(
                    'compilation',
                    30,
                    ['compilationID' => $compilationID]
                );
                if (empty($compilationPhotos)) {
                    View::render('404');
                } else {
                    $compilation['photos'] = $compilationPhotos;
                    View::render('compilation', $compilation);
                }
            }
        }
    }
}
