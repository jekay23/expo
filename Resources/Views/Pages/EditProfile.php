<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class EditProfile
{
    public static function render(bool &$stickFooter, $user)
    {
        $stickFooter = false;
        $varNames = null;
        if (isset($user)) {
            $userID = $user['userID'];
            $profileName = $user['name'];
            $email = $user['email'];
            $avatarLocation = $user['avatarLocation'] ?? '/image/defaultAvatar.jpg';
            $pronounSelector = ['none' => '', 'he' => '', 'she' => ''];
            $pronounSelector[$user['pronoun']] = 'selected';
            $bio = $user['bio'];
            $contact = $user['contact'];
            $varNames = ['userID', 'profileName', 'email', 'avatarLocation', 'pronounSelector', 'bio', 'contact'];
        }
        View::requireTemplate('editProfile', 'Page', compact($varNames));
    }
}
