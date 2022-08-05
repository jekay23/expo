<?php

namespace Expo\App\Models;

use Exception;
use Expo\App\Http\Controllers\Authentication;

class QueryBuilder
{
    /**
     * @throws Exception
     */
    public static function getPhotoDetails(int $photoID): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            throw new Exception('Unable to connect to server. Please try again later or contact support.');
        }

        $photo = Photos::getPhoto($photoID);

        $user = Users::getUserData($photo['addedBy']);
        if (!$user) {
            return [false, null];
        }
        $photo['authorID'] = $photo['addedBy'];
        unset($photo['addedBy']);
        $photo['authorName'] = $user['name'];
        if (isset($user['avatarLocation']) && $user['isHiddenAvatar'] === '0') {
            $photo['authorAvatarLocation'] = '/uploads/photos/' . $user['avatarLocation'];
        } else {
            $photo['authorAvatarLocation'] = '/image/defaultAvatar.jpg';
        }

        $userID = Authentication::getUserIdFromCookie();
        switch ($userID) {
            case 0:
                $photo['likeStatus'] = 'notSignedIn';
                break;
            case $photo['authorID']:
                $photo['likeStatus'] = 'author';
                break;
            default:
                $likeSet = Likes::checkLike($userID, $photoID);
                if ($likeSet) {
                    $photo['likeStatus'] = 'liked';
                } else {
                    $photo['likeStatus'] = 'notLiked';
                }
        }

        return [true, $photo];
    }

    /**
     * @throws Exception
     */
    public static function getTextFields(string $type, int $quantity, array $args = null): array
    {
        if ('filters' === $type) {
            $filters = [
                ['name' => 'По дате публикации', 'href' => ''],
                ['name' => 'По полярности', 'href' => ''],
                ['name' => 'По выставкам', 'href' => '']
            ];
            return [true, $filters];
        }

        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $users = Users::getProfiles($type, $quantity, $args);
        return [true, $users];
    }

    public static function getProfileData(int $userID, bool $accessHidden = false): array
    {
        $user = Users::getUserData($userID, $accessHidden);

        if ($user) {
            $photos = Photos::getUserPhotos($userID);
            $user['numOfPhotos'] = count($photos);
            $user['numOfLikes'] = Likes::countLikes('profile', $userID);
            $user['photos'] = $photos;
            if (isset($user['avatarLocation']) && $user['isHiddenAvatar'] === '0') {
                $user['avatarLocation'] = '/uploads/photos/' . $user['avatarLocation'];
            } else {
                $user['avatarLocation'] = '/image/defaultAvatar.jpg';
            }
            if ($user['isHiddenBio'] === '1') {
                $user['bio'] = '';
            }
            return [true, $user];
        } else {
            return [false, null];
        }
    }

    public static function addPhoto(int $userID, string $location)
    {
        $authorName = Users::getName($userID);
        Photos::savePhoto($userID, $location, $authorName);
    }

    protected static function executeQuery(QueryObject $query, bool $yields = true)
    {
        ob_start();
        echo $query;
        $querySting = ob_get_clean();

        $statement = DataBaseConnection::executeQuery($querySting);
        if ($yields) {
            return $statement->fetchAll();
        } else {
            return [];
        }
    }
}
