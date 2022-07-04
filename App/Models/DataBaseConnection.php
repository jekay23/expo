<?php

namespace Expo\App\Models;

use PDO;
use PDOException;

class DataBaseConnection
{
    private static $connection = null;

    public static function open(): array
    {
        list($host, $dbname, $username, $password) = DataBaseCredentials::getCredentials();
        if (null != self::$connection) {
            return [true, 'Connection already open'];
        }
        try {
            self::$connection = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return [true, 'Connection successful'];
        } catch (PDOException $e) {
            return [false, 'Connection failed' . $e->getMessage()];
        }
    }

    public static function close()
    {
        self::$connection = null;
    }

    public static function makeSureConnectionIsOpen(): bool
    {
        if (null === self::$connection) {
            $connectionAttempt = self::open();
            if (!$connectionAttempt[0]) {
                return false;
            }
        }
        return true;
    }

    public static function executeStatement(string $query): \PDOStatement
    {
        $statement = DataBaseConnection::$connection->prepare($query);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        return $statement;
    }
}
