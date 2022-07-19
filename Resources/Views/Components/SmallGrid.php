<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class SmallGrid
{
    public static function render(string $headerText, array $photos, int $photosPerBlock = 3)
    {
        $numOfBlocks = self::getNumOfBlocks($photos, $photosPerBlock);
        $blocks = self::fillBlocksWithPhotos($numOfBlocks, $photos, $photosPerBlock);
        View::requireTemplate('smallGrid', 'Component', compact('headerText', 'blocks'));
    }

    private static function getNumOfBlocks(array $photos, int $photosPerBlock): int
    {
        return intdiv(count($photos) + ($photosPerBlock - 1), $photosPerBlock);
    }

    private static function fillBlocksWithPhotos(int $numOfBlocks, array $photos, int $photosPerBlock): array
    {
        $blocks = [];
        for ($i = 0; $i < ($numOfBlocks - 1); $i++) {
            $blocks[$i] = [];
            for ($j = 0; $j < $photosPerBlock; $j++) {
                $blocks[$i][] = $photos[$photosPerBlock * $i + $j];
            }
        }
        self::fillLastBlock($blocks, $numOfBlocks, $photos, $photosPerBlock);
        return $blocks;
    }

    private static function fillLastBlock(array &$blocks, int $numOfBlocks, array $photos, int $photosPerBlock)
    {
        $blocks[$numOfBlocks - 1] = [];
        for ($j = 0; $j < $photosPerBlock; $j++) {
            if (isset($photos[$photosPerBlock * ($numOfBlocks - 1) + $j])) {
                $blocks[$numOfBlocks - 1][] = $photos[$photosPerBlock * ($numOfBlocks - 1) + $j];
            } else {
                break;
            }
        }
    }
}
