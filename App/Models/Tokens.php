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
        self::executeQuery($query);
    }
}
