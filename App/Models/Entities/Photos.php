<?php

namespace Expo\App\Models\Entities;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Models\QueryObject as QO;
use Expo\App\Models\Entity;

class Photos extends Entity
{
    /**
     * @throws Exception
     */
    public static function getPhotos(string $type, int $quantity = 12, array $args = null): array
    {
        self::prepareExecution();
        $query = QO::select()->table('Users')->join('RIGHT', 'Photos', 'userID', 'addedBy');
        $query->columns('photoID', 'location', 'altText', 'isHiddenByEditor', 'isHiddenByUser', 'isHiddenProfile');
        switch ($type) {
            case 'latest':
                $query->orderBy(['uploadTime', 'DESC']);
                break;
            case 'compilation':
                if (isset($args['compilationID'])) {
                    $compilationID = $args['compilationID'];
                    $query->join('RIGHT', 'CompilationItems', 'photoID');
                    $query->where(['compilationID', $compilationID]);
                }
                break;
            case 'best':
                $query->addColumns(QO::count('TL1.userID', 'likes'), 'uploadTime');
                $query->join('RIGHT', 'Likes', 'photoID');
                $query->groupBy('photoID')->orderBy(['likes', 'DESC']);
                break;
            case 'all':
                $query->addColumns('addedBy', 'uploadTime', 'isHiddenByEditor', 'isHiddenByUser');
                return self::executeQuery($query);
        }
        $query->where(['isHiddenProfile', 0], ['isHiddenByEditor', 0], ['isHiddenByUser', 0]);
        $query->limit($quantity);

        $photos = self::executeQuery($query);
        foreach ($photos as &$photo) {
            if (!isset($photo['photoID'])) {
                for ($i = 1; $i < $query->getNumOfJoins(); $i++) {
                    if (isset($photo["TL$i.photoID"])) {
                        $photo['photoID'] = $photo["TL$i.photoID"];
                        break;
                    } elseif (isset($photo["TR$i.photoID"])) {
                        $photo['photoID'] = $photo["TR$i.photoID"];
                        break;
                    }
                }
            }
        }
        $likedPhotoIds = Likes::getUserLikesArray(Authentication::getUserIdFromCookie());
        foreach ($photos as &$photo) {
            $photo['liked'] = in_array($photo['photoID'], $likedPhotoIds);
        }
        return $photos;
    }

    /**
     * @throws Exception
     */
    public static function getPhoto(int $photoID): array
    {
        self::prepareExecution();
        $query = QO::select()->table('Photos')->columns('photoID', 'location', 'altText', 'addedBy');
        $query->where(['photoID', $photoID]);
        $photos = self::executeQuery($query);
        if (!empty($photos)) {
            return $photos[0];
        } else {
            return [];
        }
    }

    /**
     * @throws Exception
     */
    public static function getPhotoDetails(int $photoID): array
    {
        self::prepareExecution();
        $photo = Photos::getPhoto($photoID);
        if (empty($photo)) {
            return [];
        }
        $user = Users::getUserData($photo['addedBy']);
        if (empty($user)) {
            return [];
        }
        $photo['authorID'] = $photo['addedBy'];
        unset($photo['addedBy']);
        $photo['authorName'] = $user['name'];
        $photo['authorAvatarLocation'] = $user['avatarLocation'];

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
        return $photo;
    }

    /**
     * @throws Exception
     */
    public static function getUserPhotos(int $userID): array
    {
        self::prepareExecution();
        $query = QO::select()->table('Photos')->columns('photoID', 'location', 'altText')->where(['addedBy', $userID]);
        $query->orderBy(['uploadTime', 'DESC'])->limit(24);
        $photos = self::executeQuery($query);
        $likedPhotoIds = Likes::getUserLikesArray(Authentication::getUserIdFromCookie());
        foreach ($photos as &$photo) {
            $photo['liked'] = in_array($photo['photoID'], $likedPhotoIds);
        }
        return $photos;
    }

    /**
     * Get all compilations' items info for admin app
     * @throws Exception
     */
    public static function getCompilationItems(int $compilationID): array
    {
        self::prepareExecution();
        $query = QO::select()->table('Photos');
        $query->join('RIGHT', 'CompilationItems', 'photoID');
        $query->columns('TR1.photoID', 'location', 'additionTime', 'isHiddenByEditor', 'isHiddenByUser');
        $query->where(['compilationID', $compilationID]);
        return self::executeQuery($query);
    }

    /**
     * @throws Exception
     */
    public static function hide(int $photoID, bool $value)
    {
        self::prepareExecution();
        $query = QO::update()->table('Photos');
        $query->columns('isHiddenByEditor')->values(($value ? 1 : 0))->where(['photoID', $photoID]);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    private static function savePhoto(int $userID, string $location, string $authorName)
    {
        self::prepareExecution();
        $query = QO::insert()->table('Photos')->columns('location', 'addedBy', 'altText');
        $query->values($location, $userID, "Фото пользователя $authorName");
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function addPhoto(int $userID, string $location)
    {
        self::prepareExecution();
        $authorName = Users::getName($userID);
        Photos::savePhoto($userID, $location, $authorName);
    }
}
