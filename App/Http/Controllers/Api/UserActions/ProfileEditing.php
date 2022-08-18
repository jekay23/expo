<?php

namespace Expo\App\Http\Controllers\Api\UserActions;

use Exception;
use Expo\App\Http\Controllers\Api;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\App\Models\Entities\Users;
use Expo\Config\ExceptionWithUserMessage;

class ProfileEditing
{
    /**
     * @throws Exception
     */
    public static function editProfile()
    {
        $post = $_POST;
        $userID = Authentication::getUserIdFromCookie();
        try {
            HTTPQueryHandler::validateAndProcessPost($post);
        } catch (ExceptionWithUserMessage $e) {
            Api::openPageWithUserMessage("/profile/$userID/edit", $e->getMessage());
            exit;
        }
        $user = [
            'userID' => $userID,
            'name' => $post['name'],
            'pronoun' => $post['pronoun'],
            'bio' => $post['bio'],
            'contact' => $post['contact']
        ];
        Users::updateProfileData($user);
        Api::openPageWithUserMessage("/profile/$userID", 'Данные профиля обновлены', 'green');
    }
}
