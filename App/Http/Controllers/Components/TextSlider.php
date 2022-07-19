<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\QueryBuilder;
use Expo\Resources\Views;

class TextSlider
{
    public static function prepare(string $headerText, string $type, int $quantity, int $compilationID = null)
    {
        if ('compilation' === $type) {
            $args = ['compilationID' => $compilationID];
        } else {
            $args = null;
        }
        list($status, $textFields) = QueryBuilder::getTextFields($type, $quantity, $args);
        if ($status) {
            if ('filters' != $type) {
                foreach ($textFields as &$textField) {
                    $textField['href'] = '/profile/' . $textField['userID'];
                    unset($textField['userID']);
                }
            }
            Views\Components\TextSlider::render($headerText, $textFields);
        }
    }
}
