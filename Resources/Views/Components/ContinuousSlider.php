<?php

namespace Expo\Resources\Views\Components;

class ContinuousSlider
{
    public static function render(string $headerText, array $photos)
    {
        require 'Templates/continuousSlider.php';
    }
}
