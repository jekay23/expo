<?php

namespace Expo\App\Models;

use Exception;
use Expo\App\Models\QueryObject as QO;
use Expo\App\Models\QueryBuilder as QB;

class Users extends QB
{
    /**
     * @throws Exception
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
            throw new Exception('Unknown error: user created, but ID inaccessible.');
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
            throw new Exception('Unknown error: user exists, but inaccessible.');
        }
    }

    /**
     * @throws Exception
     */
    public static function checkEmailInDB(string $email): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            throw new Exception('Unable to connect to server. Please try again later or contact support.');
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

    public static function getName(int $userID): string
    {
        $query = QO::select()->table('Users')->columns('name')->where(['userID', $userID]);
        $users = self::executeQuery($query);
        $name = $users[0]['name'];
        return $name;
    }

    public static function getProfiles(string $type, int $quantity, $args): array
    {
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
        return self::executeQuery($query);
    }

    public static function getUserData(int $userID): array
    {
        $query = QO::select()->table('Users');
        $query->columns('userID', 'name', 'email', 'pronoun', 'bio', 'contact', 'avatarLocation');
        $query->where(['userID', $userID]);

        $users = self::executeQuery($query);
        return $users[0];
    }

    public static function getUsers(): array
    {
        $query = QO::select()->table('Users');
        $query->columns(
            'userID',
            'email',
            'name',
            'pronoun',
            'bio',
            'contact',
            'signUpDate',
            'isEditor',
            'isAdmin',
            'avatarLocation',
            'isHiddenProfile',
            'isHiddenBio',
            'isHiddenAvatar'
        );

        $users = self::executeQuery($query);
        foreach ($users as &$user) {
            if ($user['isEditor']) {
                $user['accessLevel'] = 2;
            } elseif ($user['isAdmin']) {
                $user['accessLevel'] = 3;
            } else {
                $user['accessLevel'] = 1;
            }
        }
        return $users;
    }

    /**
     * @param int $userID
     * @return int
     * @throws Exception
     * Levels:
     *  0: anonymous user
     *  1: authenticated user
     *  2: editor
     *  3: admin
     */
    public static function getUserLevel(int $userID): int
    {
        $query = QO::select()->table('Users');
        $query->columns('isEditor', 'isAdmin')->where(['userID', $userID]);

        $users = self::executeQuery($query);
        if (empty($users)) {
            return 0;
        }
        if ($users[0]['isAdmin']) {
            return 3;
        }
        if ($users[0]['isEditor']) {
            return 2;
        }
        return 1;
    }
}
