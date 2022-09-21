<?php

namespace Expo\Resources\Views\Components;

use Expo\Resources\Views\Renderable;
use Expo\Resources\Views\View;

class Like extends Renderable
{
    private bool $liked;

    public function __construct(bool $liked = false)
    {
        $this->liked = $liked;
    }

    public function render()
    {
        ob_start();
        $liked = $this->liked;
        View::requireTemplate('like', 'Component', compact('liked'));
        return ob_get_clean();
    }
}
