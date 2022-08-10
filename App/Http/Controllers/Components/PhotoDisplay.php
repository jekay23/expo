<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\Entities\Photos;
use Expo\Resources\Views;

class PhotoDisplay
{
    public static function prepare(
        string $appearanceType,
        string $headerText,
        string $dataType,
        int $quantity,
        int $compilationID = null
    ) {
        if ('compilation' === $dataType) {
            $args = ['compilationID' => $compilationID];
        } else {
            $args = null;
        }
        $photos = Photos::getPhotos($dataType, $quantity, $args);
        if (!empty($photos)) {
            switch ($appearanceType) {
                case 'carousel':
                    Views\Components\Carousel::render($headerText, $photos);
                    break;
                case 'slider':
                    Views\Components\ContinuousSlider::render($headerText, $photos);
                    break;
                case 'grid':
                    Views\Components\SmallGrid::render($headerText, $photos);
            }
        }
    }
}
