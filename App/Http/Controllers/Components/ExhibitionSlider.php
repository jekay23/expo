<?php

namespace Expo\App\Http\Controllers\Components;

use Expo\App\Models\Entities\Compilations;
use Expo\App\Models\Entities\Photos;
use Expo\Resources\Views;

class ExhibitionSlider
{
    /**
     * @throws \Exception
     */
    public static function prepare(string $headerText, string $type, int $quantity, int $compilationID = null)
    {
        if ('compilation' === $type) {
            $args = ['compilationID' => $compilationID];
        } else {
            $args = null;
        }
        $photos = PhotoDisplay::generatePhotosArray(Photos::getPhotos('compilation', $quantity, $args));
        if (!empty($photos)) {
            $compilation = Compilations::getCompilationDetails($compilationID);
            if (!empty($compilation)) {
                $compilation['compilationID'] = $compilationID;
                Views\Components\ExhibitionSlider::render(
                    $headerText,
                    $photos,
                    $compilation
                );
            }
        }
    }
}
