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

    public static function requirePhotos(string $type, int $quantity, array $args = null): array
    {
        if (null == self::$connection) {
            $connectionAttempt = self::open();
            if (!$connectionAttempt[0]) {
                return array(false, null);
            }
        }

        $query = "SELECT location, altText FROM Photos";
        if ('latest' == $type) {
            $query .= " ORDER BY uploadTime DESC";
        } elseif ('best' == $type) {
            $query .= " ORDER BY likes DESC, uploadTime DESC";
        } elseif ('compilation' == $type) {
            if (isset($args['exhibitionNumber'])) {
                $exhibitionNumber = $args['exhibitionNumber'];
                $query .= " RIGHT JOIN CompilationItems CI ON Photos.photoID = CI.photoID";
                $query .= " WHERE compilationID = $exhibitionNumber";
            }
        }
        $query .= " LIMIT $quantity";

        // TODO: now if none of the conditions are met, the query just asks for $quantity photos, have to think about it

        $statement = self::$connection->prepare($query);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $photos = $statement->fetchAll();
        return array(true, $photos);
    }

    public static function requireExhibitionDetails(int $exhibitionNumber): array
    {
        if (null == self::$connection) {
            $connectionAttempt = self::open();
            if (!$connectionAttempt[0]) {
                return array(false, null);
            }
        }

        $query = "SELECT name, description FROM Compilations WHERE exhibitNumber=$exhibitionNumber";

        $statement = self::$connection->prepare($query);
        $statement->execute();

        $statement->setFetchMode(PDO::FETCH_ASSOC);
        $exhibitions = $statement->fetchAll();
        return array(true, $exhibitions[0]);
    }
}
