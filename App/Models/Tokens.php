<?php

namespace Expo\App\Models;

use Exception;
use Expo\App\Models\QueryObject as QO;
use Expo\App\Models\QueryBuilder as QB;

class Tokens extends QB
{
    private static array $types = ['verify', 'restore'];

    public static function add(int $userID, string $token, string $type)
    {
        if (!in_array($type, self::$types)) {
            throw new Exception('Unknown type of token');
        }
        $query = QO::insert()->table('Tokens')->columns('userID', 'token', 'type')->values($userID, $token, $type);
        self::executeQuery($query, false);
    }

    public static function check(int $userID, string $token, string $type): bool
    {
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

    public static function delete(string $token)
    {
        $query = QO::delete()->table('Tokens')->where(['token', $token]);
        self::executeQuery($query, false);
    }
}
