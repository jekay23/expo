<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class ExhibitionSlider
{
    public static function render(string $headerText, array $photos, string $exhibitionName, string $exhibitionDesc)
    {
        if (!empty($photos)) {
            if (0 !== count($photos) % 2) {
                array_pop($photos);
            }
            $rowLength = count($photos) / 2;
            $rows = [[], []];
            for ($i = 0; $i < $rowLength; $i++) {
                $rows[0][] = $photos[$i];
                $rows[1][] = $photos[$rowLength + $i];
            }
            View::requireTemplate(
                'exhibitionSlider',
                'Component',
                compact('headerText', 'exhibitionName', 'exhibitionDesc', 'rows')
            );
        }
    }
}
