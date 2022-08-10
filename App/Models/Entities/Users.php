<?php

namespace Expo\App\Models\Entities;

use Exception;
use Expo\App\Models\QueryObject as QO;
use Expo\App\Models\Entity;

class Users extends Entity
{
    /**
     * Returns userID if user with given email exists, 0 otherwise (since there's no user with id 0)
     * @throws Exception
     */
    public static function getIdByEmail(string $email): int
    {
        self::prepareExecution();
        $query = QO::select()->table('Users')->columns('userID')->where(['email', "$email"]);
        $user = self::executeQuery($query);
        return $user[0]['userID'] ?? 0;
    }

    /**
     * @throws Exception
     */
    public static function getName(int $userID): string
    {
        self::prepareExecution();
        $query = QO::select()->table('Users')->columns('name')->where(['userID', $userID]);
        $users = self::executeQuery($query);
        return $users[0]['name'];
    }

    /**
     * @throws Exception
     */
    public static function getUserData(int $userID, bool $includeHidden = false): array
    {
        self::prepareExecution();
        $query = QO::select()->table('Users');
        $query->columns(
            'userID',
            'name',
            'email',
            'pronoun',
            'bio',
            'contact',
            'avatarLocation',
            'isHiddenBio',
            'isHiddenAvatar'
        );
        if ($includeHidden) {
            $query->where(['userID', $userID]);
        } else {
            $query->where(['userID', $userID], ['isHiddenProfile', 0]);
        }
        $users = self::executeQuery($query);
        if (empty($users)) {
            return [];
        }
        $user = $users[0];
        if (isset($user['avatarLocation']) && $user['isHiddenAvatar'] === '0') {
            $user['avatarLocation'] = '/uploads/photos/' . $user['avatarLocation'];
        } else {
            $user['avatarLocation'] = '/image/defaultAvatar.jpg';
        }
        return $user;
    }

    /**
     * @throws Exception
     */
    public static function getProfilePageData(int $userID, bool $includeHidden = false): array
    {
        self::prepareExecution();
        $user = self::getUserData($userID, $includeHidden);
        if (!empty($user)) {
            $photos = Photos::getUserPhotos($userID);
            $user['numOfPhotos'] = count($photos);
            $user['numOfLikes'] = Likes::countLikes($userID);
            $user['photos'] = $photos;
            if ($user['isHiddenBio'] === '1') {
                $user['bio'] = '';
            }
            return $user;
        } else {
            throw new Exception('Пользователь не существует', 1);
        }
    }

    /**
     * @throws Exception
     */
    public static function getProfileNamesAndIds(string $type, int $quantity, $args): array
    {
        self::prepareExecution();
        $query = QO::select()->table('Users')->columns('name', 'userID');

        switch ($type) {
            case 'latest':
                $query->orderBy(['signUpDate', 'DESC']);
                break;
            case 'compilation':
                if (isset($args['compilationID'])) {
                    $compilationID = $args['compilationID'];
                    $query .= ''; // expand if needed
                }
                break;
            case 'best':
                $query .= ''; // expand if needed
                break;
        }
        $query->where(['isHiddenProfile', 0]);
        $query->limit($quantity);
        return self::executeQuery($query);
    }

    /**
     * Levels:
     * - 0: anonymous user
     * - 1: authenticated user
     * - 2: editor
     * - 3: admin
     * @param int $userID
     * @return int
     * @throws Exception
     */
    public static function getAccessLevel(int $userID): int
    {
        self::prepareExecution();
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

    /**
     * Get all users' info for admin app
     * @throws Exception
     */
    public static function getUsers(): array
    {
        self::prepareExecution();
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
            if ($user['isAdmin']) {
                $user['accessLevel'] = 3;
            } elseif ($user['isEditor']) {
                $user['accessLevel'] = 2;
            } else {
                $user['accessLevel'] = 1;
            }
        }
        return $users;
    }

    /**
     * @throws Exception
     */
    public static function create(array $credentials): int
    {
        self::prepareExecution();
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
        $users = self::executeQuery($query);
        if (isset($users[0]['userID'])) {
            return $users[0]['userID'];
        } else {
            throw new Exception('Unknown error: user created, but ID inaccessible.', 0);
        }
    }

    /**
     * @throws Exception
     */
    public static function authenticate(int $userID, string $passwordHash): bool
    {
        self::prepareExecution();
        $query = QO::select()->table('Users')->columns('passwordHash')->where(['userID', $userID]);
        $users = self::executeQuery($query);
        if (isset($users[0]['passwordHash'])) {
            return ($passwordHash == $users[0]['passwordHash']);
        } else {
            throw new Exception("User either doesn't exist or has no password hash.", 0);
        }
    }

    /**
     * @throws Exception
     */
    public static function updateProfileData(array $newData)
    {
        self::prepareExecution();
        $fields = ['name', 'pronoun', 'bio', 'contact', 'email', 'passwordHash'];
        foreach ($fields as $field) {
            if (isset($newData[$field])) {
                $query = QO::update()->table('Users')->columns($field)->values($newData[$field]);
                $query->where(['userID', $newData['userID']]);
                self::executeQuery($query, false);
            }
        }
    }

    /**
     * @throws Exception
     */
    public static function updateAvatar(int $userID, string $location)
    {
        self::prepareExecution();
        $query = QO::update()->table('Users')->columns('avatarLocation')->values($location);
        $query->where(['userID', $userID]);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function updateBool(int $userID, string $field, bool $value)
    {
        self::prepareExecution();
        $query = QO::update()->table('Users');
        $query->columns($field)->values(($value ? 1 : 0))->where(['userID', $userID]);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function updateAccessLevel(int $userID, int $value)
    {
        self::prepareExecution();
        $isEditor = [0, 0, 1, 0];
        $isAdmin = [0, 0, 0, 1];

        $query = QO::update()->table('Users')->columns('isEditor')->where(['userID', $userID]);
        $query->values($isEditor[$value]);
        self::executeQuery($query, false);

        $query = QO::update()->table('Users')->columns('isAdmin')->where(['userID', $userID]);
        $query->values($isAdmin[$value]);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function updatePassword(int $userID, string $passwordHash)
    {
        self::prepareExecution();
        $query = QO::update()->table('Users')->columns('passwordHash')->where(['userID', $userID]);
        $query->values($passwordHash);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function verifyEmail(int $userID)
    {
        self::prepareExecution();
        $query = QO::update()->table('Users')->columns('isEmailVerified')->where(['userID', $userID]);
        $query->values(1);
        self::executeQuery($query, false);
    }
}
