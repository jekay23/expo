<?php

namespace Expo\App\Models\Entities;

use Exception;
use Expo\App\Models\QueryObject as QO;
use Expo\App\Models\Entity;

class Likes extends Entity
{
    /**
     * @throws Exception
     */
    public static function checkLike($userID, $photoID): bool
    {
        self::prepareExecution();
        $query = QO::select()->table('Likes')->columns('likeID')->where(['userID', $userID], ['photoID', $photoID]);
        $likes = self::executeQuery($query);
        if (1 == count($likes)) {
            return true;
        } elseif (0 == count($likes)) {
            return false;
        } else {
            throw new Exception("More than one like on photo $photoID by user $userID");
        }
    }

    /**
     * @throws Exception
     */
    public static function addLike(int $userID, int $photoID)
    {
        self::prepareExecution();
        $query = QO::insert()->table('Likes')->columns('userID', 'photoID')->values($userID, $photoID);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function removeLike(int $userID, int $photoID)
    {
        self::prepareExecution();
        $query = QO::delete()->table('Likes')->where(['userID', $userID], ['photoID', $photoID]);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function countLikes(int $userID): int
    {
        self::prepareExecution();
        $query = QO::select()->table('Photos')->join('RIGHT', 'Likes', 'photoID');
        $query->where(['addedBy', $userID]);
        $query->columns(QO::count('likeID', 'likes'))->groupBy('addedBy');
        $result = self::executeQuery($query);
        if (empty($result)) {
            return 0;
        } else {
            return $result[0]['likes'];
        }
    }

    /**
     * @throws Exception
     */
    public static function addLikeByName(int $userID, string $name)
    {
        self::prepareExecution();
        $photoID = self::getPhotoIdByName($name);
        self::addLike($userID, $photoID);
    }

    /**
     * @throws Exception
     */
    public static function removeLikeByName(int $userID, string $name)
    {
        self::prepareExecution();
        $photoID = self::getPhotoIdByName($name);
        self::removeLike($userID, $photoID);
    }

    /**
     * @throws Exception
     */
    public static function getPhotoIdByName(string $name): int
    {
        self::prepareExecution();
        $query = QO::select()->table('Photos')->columns('photoID')->where(['location', $name]);
        $photos = self::executeQuery($query);
        return $photos[0]['photoID'];
    }

    /**
     * @throws Exception
     */
    public static function getUserLikesArray(int $userID): array
    {
        self::prepareExecution();
        $query = QO::select()->table('Likes')->columns('photoID')->where(['userID', $userID]);
        $likes = self::executeQuery($query);
        $likedPhotoIds = [];
        foreach ($likes as $like) {
            $likedPhotoIds[] = $like['photoID'];
        }
        return $likedPhotoIds;
    }
}
