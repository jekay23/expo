<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class Carousel
{
    public static function render(string $headerText, array $photos)
    {
        // bootstrap carousel needs one of its items to be active
        foreach ($photos as &$photo) {
            $photo['carouselStatus'] = '';
        }
        $photos[0]['carouselStatus'] = 'active';
        View::requireTemplate('carousel', 'Component', compact('headerText', 'photos'));
    }
}
