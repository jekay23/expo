<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\View;

class EditProfile
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
            $pronounSelector = ['none' => '', 'he' => '', 'she' => ''];
            $pronounSelector[$user['pronoun']] = 'selected';
            $bio = $user['bio'];
            $contact = $user['contact'];
            $compact = compact('userID', 'profileName', 'email', 'avatarLocation', 'pronounSelector', 'bio', 'contact');
        }
        View::requireTemplate('editProfile', 'Page', $compact);
    }
}
