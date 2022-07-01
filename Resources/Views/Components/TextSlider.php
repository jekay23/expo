<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class TextSlider
{
    public static function render(string $headerText, array $textFields)
    {
        View::requireTemplate('textSlider', 'Component', compact('headerText', 'textFields'));
    }
}
