<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\Components\SmallGrid;
use Expo\Resources\Views\View;

class Profile
{
    public static function render(bool &$stickFooter, $user)
    {
        $stickFooter = true;
        $varNames = null;
        if (isset($user)) {
            $profileName = $user['name'];
            $avatarLocation = $user['avatarLocation'] ?? '/image/defaultAvatar.jpg';
            $pronoun = $user['pronoun'];
            $bio = $user['bio'];
            $numOfPhotos = $user['numOfPhotos'];
            $numOfLikes = 0;
            $buttonName = 'Договориться о фотосессии';
            $varNames = ['profileName', 'avatarLocation', 'pronoun', 'bio', 'numOfPhotos', 'numOfLikes', 'buttonName'];
        }
        View::requireTemplate('profile', 'Page', compact($varNames));

        SmallGrid::render('', $user['photos']);
    }
}
