<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\DataBaseConnection;
use Expo\Resources\Views;

// TODO: rename controller classes and extend the namespace here and in other View Components classes

class ExhibitionSlider
{
    public static function renderComponent(string $headerText, int $compilationID, int $quantity)
    {
        $args = array('compilationID' => $compilationID);
        list($status, $photos) = DataBaseConnection::requirePhotos('compilation', $quantity, $args);
        if ($status) {
            list($status, $compilation) = DataBaseConnection::requireCompilationDetails($compilationID);
            if ($status) {
                Views\Components\ExhibitionSlider::renderComponent(
                    $headerText,
                    $photos,
                    $compilation['name'],
                    $compilation['description']
                );
            }
        }
    }
}
