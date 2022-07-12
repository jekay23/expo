<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class SmallGrid
{
    public static function render(string $headerText, array $photos, int $photosPerBlock = 3)
    {
        // the last block may not be full
        $numOfBlocks = intdiv(count($photos) + ($photosPerBlock - 1), $photosPerBlock);
        $blocks = [];
        // fill all the blocks except for the last one
        for ($i = 0; $i < ($numOfBlocks - 1); $i++) {
            $blocks[$i] = [];
            for ($j = 0; $j < $photosPerBlock; $j++) {
                $blocks[$i][] = $photos[$photosPerBlock * $i + $j];
            }
        }
        // fill the last block
        $blocks[$numOfBlocks - 1] = [];
        for ($j = 0; $j < $photosPerBlock; $j++) {
            if (isset($photos[$photosPerBlock * ($numOfBlocks - 1) + $j])) {
                $blocks[$numOfBlocks - 1][] = $photos[$photosPerBlock * ($numOfBlocks - 1) + $j];
            } else {
                break;
            }
        }
        View::requireTemplate('smallGrid', 'Component', compact('headerText', 'blocks'));
    }
}
