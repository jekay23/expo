<?php

namespace Expo\Resources\Views\Components;

class ExhibitionSlider
{
    public static function render(string $headerText, array $photos, string $exhibitionName, string $exhibitionDesc)
    {
        if (0 === count($photos) % 2) {
            $rowLength = count($photos) / 2;
            $rows = [[], []];
            for ($i = 0; $i < $rowLength; $i++) {
                $rows[0][] = $photos[$i];
                $rows[1][] = $photos[$rowLength + $i];
            }
            require 'Templates/exhibitionSlider.php';
        }
    }
}
