<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class SmallGrid
{
    public static function render(string $headerText, array $photos)
    {
        if (0 === count($photos) % 6) {
            $numOfTriples = count($photos) / 3;
            $triples = [];
            for ($i = 0; $i < $numOfTriples; $i++) {
                $triples[$i] = [$photos[3 * $i], $photos[3 * $i + 1], $photos[3 * $i + 2]];
            }
            View::requireTemplate('smallGrid', 'Component', compact('headerText', 'triples'));
        }
    }
}
