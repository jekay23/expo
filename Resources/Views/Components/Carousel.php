<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class Carousel
{
    public static function render(string $headerText, array $photos)
    {
        if (!empty($photos)) {
            foreach ($photos as $photo) {
                $photo->addClass('mmd-carousel-image');
                $photo->setWrapper(['carouselStatus' => '']);
            }
            $photos[0]->setWrapper(['carouselStatus' => 'active']);
            View::requireTemplate('carousel', 'Component', compact('headerText', 'photos'));
        }
    }
}
