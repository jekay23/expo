<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\QueryBuilder;
use Expo\Resources\Views;

// TODO: rename controller classes and extend the namespace here and in other View Components classes

class ExhibitionSlider
{
    public static function prepare(string $headerText, string $type, int $quantity, int $compilationID = null)
    {
        if ('compilation' === $type) {
            $args = ['compilationID' => $compilationID];
        } else {
            $args = null;
        }
        list($status, $photos) = QueryBuilder::getPhotos('compilation', $quantity, $args);
        if ($status) {
            list($status, $compilation) = QueryBuilder::getCompilationDetails($compilationID);
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
