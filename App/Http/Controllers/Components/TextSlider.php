<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\DataBaseConnection;
use Expo\Resources\Views;

class TextSlider
{
    public static function renderComponent(string $headerText, string $type, int $quantity)
    {
        list($status, $textFields) = DataBaseConnection::requireText($type, $quantity);
        if ($status) {
            Views\Components\TextSlider::renderComponent($headerText, $textFields);
        }
    }
}
