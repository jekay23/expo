<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\Components\SmallGrid;
use Expo\Resources\Views\View;

class Profile
{
    public static function render(bool &$stickFooter, $user)
    {
        $stickFooter = true;
        $numOfPhotos = 0;
        $varNames = null;
        if (isset($user)) {
            $profileName = $user['name'];
            $avatarLocation = $user['avatarLocation'];
            $pronoun = $user['pronoun'];
            $bio = $user['bio'];
            $numOfPhotos = $user['numOfPhotos'];
            if ($numOfPhotos > 6) {
                $stickFooter = false;
            }
            $numOfLikes = 0;
            $button = $user['isProfileOwner'] ? [
                'name' => 'Загрузить фотографии',
                'href' => 'onclick="location.href=' . "'" . 'http://' . $_SERVER['HTTP_HOST'] . '/upload' . "'" . '"'
            ] : [
                'name' => 'Договориться о фотосессии',
                'href' => 'http://' . $_SERVER['HTTP_HOST'] . '/profile/' . $user['userID'] . '#contact'
            ];
            $ownProfile = $user['isProfileOwner'] ? [
                'status' => true,
                'editLink' => 'http://' . $_SERVER['HTTP_HOST'] . '/profile/' . $user['userID'] . '/edit'
            ] : [
                'status' => false
            ];
            $varNames = ['profileName', 'avatarLocation', 'pronoun', 'bio',
                         'numOfPhotos', 'numOfLikes', 'button', 'ownProfile'];
        }
        View::requireTemplate('profile', 'Page', compact($varNames));

        if ($numOfPhotos > 0) {
            SmallGrid::render('', $user['photos']);
        } else {
            View::requireTemplate('noPhotos', 'Component');
        }
    }
}
