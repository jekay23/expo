<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\Compilations;
use Expo\App\Models\Photos;
use Expo\Resources\Views;

class ExhibitionSlider
{
    public static function prepare(string $headerText, string $type, int $quantity, int $compilationID = null)
    {
        if ('compilation' === $type) {
            $args = ['compilationID' => $compilationID];
        } else {
            $args = null;
        }
        list($status, $photos) = Photos::getPhotos('compilation', $quantity, $args);
        if ($status) {
            list($status, $compilation) = Compilations::getCompilationDetails($compilationID);
            if ($status) {
                Views\Components\ExhibitionSlider::render(
                    $headerText,
                    $photos,
                    $compilation['name'],
                    $compilation['description']
                );
            }
        }
    }
}
