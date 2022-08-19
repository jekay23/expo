<?php

namespace Expo\App\Http\Controllers\Components;

use Exception;
use Expo\App\Models\Entities\Photos;
use Expo\Resources\Views;
use Expo\Resources\Views\Components\Photo;

class PhotoDisplay
{
    /**
     * @throws Exception
     */
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
        $photos = self::generatePhotosArray(Photos::getPhotos($dataType, $quantity, $args));
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

    public static function generatePhotosArray(array $photos): array
    {
        $result = [];
        foreach ($photos as $photo) {
            $result[] = new Photo($photo);
        }
        return $result;
    }
}
