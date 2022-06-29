<?php

namespace Expo\Resources\Views\Components;

class Carousel
{
    public static function renderComponent(string $headerText, array $photos)
    {
        // bootstrap carousel needs one of its items to be active
        foreach ($photos as &$photo) {
            $photo['carouselStatus'] = '';
        }
        $photos[0]['carouselStatus'] = 'active';
        require 'Templates/carousel.php';
    }
}
