<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\QueryBuilder;
use Expo\Resources\Views;

class SmallGrid
{
    public static function assemble(string $headerText, string $type, int $quantity, int $compilationID = null)
    {
        if ('compilation' === $type) {
            $args = ['compilationID' => $compilationID];
        } else {
            $args = null;
        }
        list($status, $photos) = QueryBuilder::requirePhotos($type, $quantity);
        if ($status) {
            Views\Components\SmallGrid::render($headerText, $photos);
        }
    }
}