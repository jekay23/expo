<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\View;

class Like
{
    public static function render(bool $liked)
    {
        View::requireTemplate('like', 'Component', compact('liked'));
    }
}
