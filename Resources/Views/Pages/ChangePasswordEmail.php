<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class ChangePasswordEmail
{
    public static function render(bool &$stickFooter, $user)
    {
        $stickFooter = false;
        $varNames = null;
        if (isset($user)) {
            $userID = $user['userID'];
            $profileName = $user['name'];
            $email = $user['email'];
            $avatarLocation = $user['avatarLocation'];
            $varNames = ['userID', 'profileName', 'email', 'avatarLocation'];
        }
        View::requireTemplate('changePasswordEmail', 'Page', compact($varNames));
    }
}
