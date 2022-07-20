<?php

namespace Expo\Resources\Views\Pages;

use Expo\Resources\Views\Components\SmallGrid;
use Expo\Resources\Views\View;

class Profile
{
    public static function render(bool &$stickFooter, $user)
    {
        $stickFooter = true;
        $compact = compact(null);
        if (isset($user)) {
            if ($user['numOfPhotos'] > 6) {
                $stickFooter = false;
            }
            if ($user['isProfileOwner']) {
                $button = [
                    'name' => 'Загрузить фотографии',
                    'href' => 'onclick="location.href=\'http://' . $_SERVER['HTTP_HOST'] . '/upload\'"'
                ];
            } else {
                if (empty($user['contact'])) {
                    $user['contact'] = 'Пользователь пока что не оставил своих контактов';
                }
                $button = [
                    'name' => 'Договориться о фотосессии',
                    'href' => 'data-toggle="popover" data-bs-content="' . $user['contact'] . '"'
                ];
            }
            $user['ownProfile'] = $user['isProfileOwner'] ? [
                'status' => true,
                'editLink' => 'http://' . $_SERVER['HTTP_HOST'] . '/profile/' . $user['userID'] . '/edit'
            ] : [
                'status' => false
            ];
            $compact = compact(
                'user',
                'button'
            );
        }
        View::requireTemplate('profile', 'Page', $compact);

        if ($user['numOfPhotos'] > 0) {
            SmallGrid::render('', $user['photos']);
        } else {
            View::requireTemplate('noPhotos', 'Component');
        }
    }
}
