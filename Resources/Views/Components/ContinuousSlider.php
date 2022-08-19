<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class ContinuousSlider
{
    public static function render(string $headerText, array $photos)
    {
        if (!empty($photos)) {
            foreach ($photos as $photo) {
                $photo->addClass('mmd-slider-image');
            }
            View::requireTemplate('continuousSlider', 'Component', compact('headerText', 'photos'));
        }
    }
}
