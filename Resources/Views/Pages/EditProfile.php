<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class EditProfile
{
    public static function render(bool &$stickFooter, $user)
    {
        View::requireTemplate('editProfile', 'Page', compact());
    }
}