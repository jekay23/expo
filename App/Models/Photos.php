<?php

namespace Expo\App\Models;

use Expo\App\Models\QueryObject as QO;
use Expo\App\Models\QueryBuilder as QB;

class Photos extends QB
{
    /**
     * @throws \Exception
     */
    public static function getPhotos(string $type, int $quantity = 12, array $args = null): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

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
        if ($type != 'best') {
            $query->join('LEFT', 'Likes', 'photoID');
            if ('compilation' == $type) {
                $query->addColumns('TR3.likeID');
                $query->where(['(TR3.userID = 24 OR TR3.userID IS NULL)']);
            } else {
                $query->addColumns('TR2.likeID');
                $query->where(['(TR2.userID = 24 OR TR2.userID IS NULL)']);
            }
        }
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
        return [true, $photos];
    }

    public static function getPhoto(int $photoID): array
    {
        $query = QO::select()->table('Photos')->columns('photoID', 'location', 'altText', 'addedBy');
        $query->where(['photoID', $photoID]);

        $photos = self::executeQuery($query);
        return $photos[0];
    }

    public static function savePhoto(int $userID, string $location, string $authorName)
    {
        $query = QO::insert()->table('Photos')->columns('location', 'addedBy', 'altText');
        $query->values($location, $userID, "Фото пользователя $authorName");
        self::executeQuery($query, false);
    }

    public static function getUserPhotos(int $userID): array
    {
        $query = QO::select()->table('Photos')->columns('photoID', 'location', 'altText')->where(['addedBy', $userID]);
        $query->orderBy(['uploadTime', 'DESC'])->limit(24);
        return self::executeQuery($query);
    }

    public static function getCompilationItems(int $compilationID): array
    {
        $query = QO::select()->table('Photos');
        $query->join('RIGHT', 'CompilationItems', 'photoID');
        $query->columns('TR1.photoID', 'location', 'additionTime', 'isHiddenByEditor', 'isHiddenByUser');
        $query->where(['compilationID', $compilationID]);

        return self::executeQuery($query);
    }

    public static function hide(int $photoID, bool $value)
    {
        $query = QO::update()->table('Photos');
        $query->columns('isHiddenByEditor')->values(($value ? 1 : 0))->where(['photoID', $photoID]);

        self::executeQuery($query, false);
    }
}
