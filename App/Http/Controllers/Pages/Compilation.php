<?php

/**
 * Front page controller class
 * Manages the front page, which is accessed by URL 'IP/'.
 */

namespace Expo\App\Http\Controllers\Pages;

use Expo\App\Models\Compilations;
use Expo\App\Models\Photos;
use Expo\Resources\Views\View;

class Compilation
{
    public static function prepare(array $requestList, array $requestQuery)
    {
        if (empty($requestList)) {
            View::render('404');
        } else {
            $compilationID = $requestList[0];
            list($status, $compilation) = Compilations::getCompilationDetails($compilationID);
            if (!$status || empty($compilation)) {
                View::render('404');
            } else {
                list($status, $compilationPhotos) = Photos::getPhotos(
                    'compilation',
                    30,
                    ['compilationID' => $compilationID]
                );
                if (!$status) {
                    View::render('404');
                } else {
                    $compilation['photos'] = $compilationPhotos;
                    View::render('compilation', $compilation);
                }
            }
        }
    }
}
