<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\DataBaseConnection;
use Expo\Resources\Views;

class ContinuousSlider
{
    public static function assemble(string $headerText, string $type, int $quantity)
    {
        list($status, $photos) = DataBaseConnection::requirePhotos($type, $quantity);
        if ($status) {
            Views\Components\ContinuousSlider::render($headerText, $photos);
        }
    }
}
