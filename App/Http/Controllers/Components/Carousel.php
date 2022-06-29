<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\DataBaseConnection;
use Expo\Resources\Views;

class Carousel
{
    public static function assemble(string $headerText, int $compilationID, int $quantity)
    {
        $args = array('compilationID' => $compilationID);
        list($status, $photos) = DataBaseConnection::requirePhotos('compilation', $quantity, $args);
        if ($status) {
            Views\Components\Carousel::render($headerText, $photos);
        }
    }
}
