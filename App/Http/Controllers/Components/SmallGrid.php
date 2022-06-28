<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\DataBaseConnection;
use Expo\Resources\Views;

class SmallGrid
{
    public static function renderComponent(string $headerText, string $type, int $quantity)
    {
        list($status, $photos) = DataBaseConnection::requirePhotos($type, $quantity);
        if ($status) {
            Views\Components\SmallGrid::renderComponent($headerText, $photos);
        }
    }
}