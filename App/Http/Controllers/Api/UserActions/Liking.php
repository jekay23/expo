<?php

namespace Expo\App\Http\Controllers\Api\UserActions;

use Exception;
use Expo\App\Http\Controllers\Api;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Http\Controllers\HTTPQueryHandler;
use Expo\App\Models\Entities\Likes;
use Expo\Resources\Views\Html;

class Liking
{
    /**
     * @throws Exception
     */
    public static function toggleLike(string $type)
    {
        $types = ['like', 'dislike'];
        if (!in_array($type, $types)) {
            throw new Exception('Unknown like toggle type');
        }
        $likeShouldBeSet = ('like' == $type);
        $userID = Authentication::getUserIdFromCookie();
        if (0 == $userID) {
            Api::openPageWithUserMessage('/sign-in', 'Войдите в профиль, чтобы оценивать фото');
            exit;
        }
        if (empty($_SERVER['QUERY_STRING'])) {
            header("Location: /");
            exit;
        }
        $uriQuery = [];
        parse_str($_SERVER['QUERY_STRING'], $uriQuery);
        $validUriQuery = HTTPQueryHandler::validateGet($uriQuery);
        if ($validUriQuery && isset($uriQuery['photoID'])) {
            $photoID = $uriQuery['photoID'];
            $isLikeSet = Likes::checkLike($userID, $photoID);
            if ($isLikeSet == $likeShouldBeSet) {
                Api::openPageWithUserMessage("/photo/$photoID", 'Вы уже оценивали это фото');
            } else {
                $likeShouldBeSet ? Likes::addLike($userID, $photoID) : Likes::removeLike($userID, $photoID);
                header("Location: /photo/$photoID");
            }
        } else {
            header("Location: /");
        }
    }

    /**
     * @param string $type 'like' | 'dislike'
     * @return void
     * @throws Exception
     */
    public static function quickAction(string $type)
    {
        $uriQuery = self::getUriQueryArray();
        if (isset($uriQuery['name']) && $userID = Authentication::getUserIdFromCookie()) {
            switch ($type) {
                case 'like':
                    Likes::addLikeByName($userID, $uriQuery['name']);
                    break;
                case 'dislike':
                    Likes::removeLikeByName($userID, $uriQuery['name']);
                    break;
            }
        } else {
            Html::render('404');
        }
    }

    private static function getUriQueryArray(): array
    {
        $uriQuery = [];
        parse_str($_SERVER['QUERY_STRING'], $uriQuery);
        return (HTTPQueryHandler::validateGet($uriQuery) ? $uriQuery : []);
    }
}
