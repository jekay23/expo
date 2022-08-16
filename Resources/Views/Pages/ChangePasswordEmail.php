<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class ChangePasswordEmail
{
    public static function render(bool &$stickFooter, $user)
    {
        $stickFooter = false;
        $compact = [];
        if (isset($user)) {
            $userID = $user['userID'];
            $profileName = $user['name'];
            $email = $user['email'];
            $avatarLocation = $user['avatarLocation'];
            $compact = compact('userID', 'profileName', 'email', 'avatarLocation');
        }
        View::requireTemplate('changePasswordEmail', 'Page', $compact);
    }
}
