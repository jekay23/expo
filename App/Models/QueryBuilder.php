<?php

namespace Expo\App\Models;

use Expo\App\Models\QueryObject as QO;

class QueryBuilder
{
    /**
     * @throws \Exception
     */
    public static function requirePhotos(string $type, int $quantity, array $args = null): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = QO::select()->table('Photos')->columns('location', 'altText');

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
    public static function requireCompilationDetails(int $compilationID): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = QO::select()->table('Compilations')->columns('name', 'description');
        $query->where(['compilationID', $compilationID]);

        $exhibitions = self::executeQuery($query);
        return [true, $exhibitions[0]];
    }

    /**
     * @throws \Exception
     */
    public static function requireText(string $type, int $quantity, array $args = null): array
    {
        if ('filters' === $type) {
            $filters = [
                ['name' => 'По дате публикации', 'link' => ''],
                ['name' => 'По поулярности', 'link' => ''],
                ['name' => 'По выставкам', 'link' => '']
            ];
            return [true, $filters];
        }

        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = QO::select()->table('Users')->columns('name');

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
        $query = QO::select()->table('Users')->columns('userID', 'name', 'pronoun', 'bio', 'contact', 'avatarLocation');
        $query->where(['userID', $userID]);

        $user = self::executeQuery($query);

        $query = QO::select()->table('Photos')->columns(QO::count('photoID', 'numOfPhotos'));
        $query->where(['addedBy', $userID]);

        $result = self::executeQuery($query);
        $numOfPhotos = $result[0]['numOfPhotos'];

        $result = self::executeQuery($query);
        $numOfPhotos = $result[0]['numOfPhotos'];

        $query = QO::select()->table('Photos')->columns('location, altText')->where(['addedBy', $userID]);
        $query->orderBy(['uploadTime', 'DESC'])->limit(24);

        $photos = self::executeQuery($query);

        if (isset($user[0])) {
            $user[0]['numOfPhotos'] = $numOfPhotos;
            $user[0]['photos'] = $photos;
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
