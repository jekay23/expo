<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\DataBaseConnection;
use Expo\Resources\Views;

// TODO: rename controller classes and extend the namespace here and in other View Components classes

class ExhibitionSlider
{
    public static function assemble(string $headerText, string $type, int $quantity, int $compilationID = null)
    {
        if ('compilation' == $type) {
            $args = array('compilationID' => $compilationID);
        } else {
            $args = null;
        }
        list($status, $photos) = DataBaseConnection::requirePhotos('compilation', $quantity, $args);
        if ($status) {
            list($status, $compilation) = DataBaseConnection::requireCompilationDetails($compilationID);
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
