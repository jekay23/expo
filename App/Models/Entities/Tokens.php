<?php

namespace Expo\App\Models\Entities;

use Exception;
use Expo\App\Models\QueryObject as QO;
use Expo\App\Models\Entity;

class Tokens extends Entity
{
    private static array $types = ['verify', 'restore'];

    /**
     * @throws Exception
     */
    public static function add(int $userID, string $token, string $type)
    {
        self::prepareExecution();
        if (!in_array($type, self::$types)) {
            throw new Exception('Unknown type of token');
        }
        $query = QO::insert()->table('Tokens')->columns('userID', 'token', 'type')->values($userID, $token, $type);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function check(int $userID, string $token, string $type): bool
    {
        self::prepareExecution();
        if (!in_array($type, self::$types)) {
            throw new Exception('Unknown type of token');
        }
        $query = QO::select()->table('Tokens')->columns('token')->where(['userID', $userID], ['type', $type]);
        $rows = self::executeQuery($query);
        foreach ($rows as $row) {
            if ($row['token'] == $token) {
                return true;
            }
        }
        return false;
    }

    /**
     * @throws Exception
     */
    public static function delete(string $token)
    {
        self::prepareExecution();
        $query = QO::delete()->table('Tokens')->where(['token', $token]);
        self::executeQuery($query, false);
    }
}
