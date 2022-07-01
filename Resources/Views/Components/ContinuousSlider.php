<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class ContinuousSlider
{
    public static function render(string $headerText, array $photos)
    {
        View::requireTemplate('continuousSlider', 'Component', compact('headerText', 'photos'));
    }
}
