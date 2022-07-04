<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\QueryBuilder;
use Expo\Resources\Views;

class Carousel
{
    public static function assemble(string $headerText, string $type, int $quantity, int $compilationID = null)
    {
        if ('compilation' === $type) {
            $args = ['compilationID' => $compilationID];
        } else {
            $args = null;
        }
        list($status, $photos) = QueryBuilder::requirePhotos($type, $quantity, $args);
        if ($status) {
            Views\Components\Carousel::render($headerText, $photos);
        }
    }
}
