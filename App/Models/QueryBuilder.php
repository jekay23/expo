<?php

namespace Expo\App\Models;

use Expo\App\Models\QueryObject as QO;

class QueryBuilder
{
    /**
     * @throws \Exception
     */
    public static function getPhotos(string $type, int $quantity, array $args = null): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = QO::select()->table('Photos')->columns('photoID', 'location', 'altText');

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
                $query->addColumns(QO::count('userID', 'likes'), 'uploadTime');
                $query->join('RIGHT', 'Likes', 'photoID');
                $query->groupBy('photoID')->orderBy(['likes', 'DESC']);
                break;
        }
        $query->limit($quantity);

        $photos = self::executeQuery($query);
        return [true, $photos];
    }

    /**
     * @throws \Exception
     */
    public static function getPhotoDetails(int $photoID): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            throw new \Exception('Unable to connect to server. Please try again later or contact support.');
        }

        $query = QO::select()->table('Photos')->columns('location', 'altText', 'addedBy');
        $query->where(['photoID', $photoID]);

        $photos = self::executeQuery($query);
        $photo = $photos[0];

        $query = QO::select()->table('Users')->columns('name', 'avatarLocation')->where(['userID', $photo['addedBy']]);
        $users = self::executeQuery($query);
        $photo['authorID'] = $photo['addedBy'];
        unset($photo['addedBy']);
        $photo['authorName'] = $users[0]['name'];
        if (isset($users[0]['avatarLocation'])) {
            $photo['authorAvatarLocation'] = '/uploads/photos/' . $users[0]['avatarLocation'];
        } else {
            $photo['authorAvatarLocation'] = '/image/defaultAvatar.jpg';
        }

        return [true, $photo];
    }

    /**
     * @throws \Exception
     */
    public static function getCompilationDetails(int $compilationID): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = QO::select()->table('Compilations')->columns('name', 'description');
        $query->where(['compilationID', $compilationID]);

        $compilations = self::executeQuery($query);
        return [true, $compilations[0]];
    }

    public static function getCurrentExhibition(): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = QO::select()->table('Compilations')->columns('compilationID');
        $query->orderBy(['exhibitNumber', 'DESC'])->limit(1);

        $compilations = self::executeQuery($query);
        return [true, $compilations[0]['compilationID']];
    }

    /**
     * @throws \Exception
     */
    public static function getText(string $type, int $quantity, array $args = null): array
    {
        if ('filters' === $type) {
            $filters = [
                ['name' => 'По дате публикации', 'href' => ''],
                ['name' => 'По поулярности', 'href' => ''],
                ['name' => 'По выставкам', 'href' => '']
            ];
            return [true, $filters];
        }

        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = QO::select()->table('Users')->columns('name', 'userID');

        switch ($type) {
            case 'latest':
                $query->orderBy(['signUpDate', 'DESC']);
                break;
            case 'compilation':
                if (isset($args['compilationID'])) {
                    $compilationID = $args['compilationID'];
                    $query .= ''; // TODO
                }
                break;
            case 'best':
                $query .= ''; // TODO
                break;
        }
        $query->limit($quantity);

        ob_start();
        echo $query;
        $querySting = ob_get_clean();

        $statement = DataBaseConnection::executeQuery($querySting);
        $users = $statement->fetchAll();
        return [true, $users];
    }

    /**
     * @throws \Exception
     */
    public static function checkEmailInDB(string $email): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            throw new \Exception('Unable to connect to server. Please try again later or contact support.');
        }

        $query = QO::select()->table('Users')->columns('userID')->where(['email', "$email"]);

        $user = self::executeQuery($query);
        if (!empty($user)) {
            $userID = $user[0]['userID'];
            return [true, $userID];
        } else {
            return [false, null];
        }
    }

    /**
     * @throws \Exception
     */
    public static function createUser(array $credentials): array
    {
        $query = QO::insert()->table('Users')->columns('email', 'passwordHash', 'name', 'pronoun');
        $query->values(
            $credentials['email'],
            $credentials['passwordHash'],
            $credentials['name'],
            $credentials['pronoun']
        );

        self::executeQuery($query, false);

        $email = $credentials['email'];
        $query = QO::select()->table('Users')->columns('userID')->where(['email', "$email"]);
        $user = self::executeQuery($query);
        if (isset($user[0])) {
            $userID = $user[0]['userID'];
            return [true, $userID];
        } else {
            throw new \Exception('Unknown error: user created, but ID inaccessible.');
        }
    }

    public static function authenticate(int $userID, string $passwordHash)
    {
        $query = QO::select()->table('Users')->columns('passwordHash')->where(['userID', $userID]);

        $user = self::executeQuery($query);
        if (isset($user[0])) {
            if ($passwordHash == $user[0]['passwordHash']) {
                return [true, null];
            } else {
                return [false, 'Неверная комбинация email и пароля'];
            }
        } else {
            throw new \Exception('Unknown error: user exists, but inaccessible.');
        }
    }

    public static function getProfileData(int $userID): array
    {
        $query = QO::select()->table('Users');
        $query->columns('userID', 'name', 'email', 'pronoun', 'bio', 'contact', 'avatarLocation');
        $query->where(['userID', $userID]);

        $user = self::executeQuery($query);

        $query = QO::select()->table('Photos')->columns(QO::count('photoID', 'numOfPhotos'));
        $query->where(['addedBy', $userID]);

        $result = self::executeQuery($query);
        $numOfPhotos = $result[0]['numOfPhotos'];

        $result = self::executeQuery($query);
        $numOfPhotos = $result[0]['numOfPhotos'];

        $query = QO::select()->table('Photos')->columns('photoID', 'location, altText')->where(['addedBy', $userID]);
        $query->orderBy(['uploadTime', 'DESC'])->limit(24);

        $photos = self::executeQuery($query);

        if (isset($user[0])) {
            $user[0]['numOfPhotos'] = $numOfPhotos;
            $user[0]['photos'] = $photos;
            if (isset($user[0]['avatarLocation'])) {
                $user[0]['avatarLocation'] = '/uploads/photos/' . $user[0]['avatarLocation'];
            } else {
                $user[0]['avatarLocation'] = '/image/defaultAvatar.jpg';
            }
            return [true, $user[0]];
        } else {
            return [false, null];
        }
    }

    public static function addPhoto(int $userID, string $location)
    {
        $query = QO::select()->table('Users')->columns('name')->where(['userID', $userID]);
        $users = self::executeQuery($query);
        $name = $users[0]['name'];

        $query = QO::insert()->table('Photos')->columns('location', 'addedBy', 'altText');
        $query->values($location, $userID, "Фото пользователя $name");
        self::executeQuery($query, false);
    }

    public static function updateProfileData(array $user)
    {
        $fields = ['name', 'pronoun', 'bio', 'contact', 'email', 'passwordHash'];
        foreach ($fields as $field) {
            if (isset($user[$field])) {
                $query = QO::update()->table('Users')->columns($field)->values($user[$field]);
                $query->where(['userID', $user['userID']]);
                self::executeQuery($query, false);
            }
        }
    }

    public static function updateAvatar(int $userID, string $location)
    {
        $query = QO::update()->table('Users')->columns('avatarLocation')->values($location);
        $query->where(['userID', $userID]);
        self::executeQuery($query, false);
    }

    private static function executeQuery(QueryObject $query, bool $yields = true)
    {
        ob_start();
        echo $query;
        $querySting = ob_get_clean();

        $statement = DataBaseConnection::executeQuery($querySting);
        if ($yields) {
            return $statement->fetchAll();
        } else {
            return 0;
        }
    }
}
