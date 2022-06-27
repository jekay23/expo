<?php

namespace Expo\App\Models;

use PDO;
use PDOException;

class DataBaseConnection
{
    private static $connection = null;

    public static function open()
    {
        list($host, $dbname, $username, $password) = DataBaseCredentials::getCredentials();
        if (null != self::$connection) {
            return array(true, 'Connection already open');
        }
        try {
            self::$connection = new PDO('mysql:host=' . $host . ';dbname=' . $dbname, $username, $password);
            self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return array(true, 'Connection successful');
        } catch (PDOException $e) {
            return array(false, 'Connection failed' . $e->getMessage());
        }
    }

    public static function close()
    {
        self::$connection = null;
    }

    public static function requirePhotos(string $type, int $quantity, array $args = null)
    {
        if (null == self::$connection) {
            $connectionAttempt = self::open();
            if (!$connectionAttempt[0]) {
                return array(false, null);
            }
        }

        $query = "SELECT location FROM Photos";
        if ('latest' == $type) {
            $query .= " ORDER BY uploadTime DESC";
        } elseif ('best' == $type) {
            $query .= " ORDER BY likes DESC, uploadTime DESC";
        } elseif ('compilation' == $type) {
            if (isset($args['compilationID'])) {
                $query .= "";
            }
        }

        $query .= " LIMIT $quantity";

        $statement = self::$connection->prepare($query);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $photos = $statement->fetchAll();
        return array(true, $photos);
    }
}
