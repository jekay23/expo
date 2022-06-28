<?php

namespace Expo\Resources\Views\Components;

class ContinuousSlider
{
    public static function renderComponent(string $headerText, array $photos)
    {
        require 'Templates/continuousSlider.php';
    }
}