<?php

namespace Expo\App\Models;

use Expo\App\Models\QueryObject as QO;

class Likes extends QueryBuilder
{
    public static function checkLike($userID, $photoID): bool
    {
        $query = QO::select()->table('Likes')->columns('likeID')->where(['userID', $userID], ['photoID', $photoID]);
        $likes = self::executeQuery($query);

        if (1 == count($likes)) {
            return true;
        } elseif (0 == count($likes)) {
            return false;
        } else {
            throw new \Exception("More than one like on photo $photoID by user $userID");
        }
    }

    public static function addLike($userID, $photoID)
    {
        $query = QO::insert()->table('Likes')->columns('userID', 'photoID')->values($userID, $photoID);
        self::executeQuery($query, false);
    }

    public static function removeLike($userID, $photoID)
    {
        $query = QO::delete()->table('Likes')->where(['userID', $userID], ['photoID', $photoID]);
        self::executeQuery($query, false);
    }
}
