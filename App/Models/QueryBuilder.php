<?php

namespace Expo\App\Models;

class QueryBuilder
{
    public static function requirePhotos(string $type, int $quantity, array $args = null): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = "SELECT location, altText FROM Photos";
        if ('latest' === $type) {
            $query .= " ORDER BY uploadTime DESC";
        } elseif ('compilation' === $type) {
            if (isset($args['compilationID'])) {
                $compilationID = $args['compilationID'];
                $query .= " RIGHT JOIN CompilationItems CI ON Photos.photoID = CI.photoID";
                $query .= " WHERE compilationID = $compilationID";
            }
        } elseif ('best' === $type) {
            $query = 'SELECT location, altText, COUNT(userID) AS likes, uploadTime ' .
                'FROM Photos P RIGHT JOIN Likes L ON P.photoID = L.photoID GROUP BY P.photoID ORDER BY likes DESC';
        }
        $query .= " LIMIT $quantity";

        // TODO: now if none of the conditions are met, the query just asks for $quantity photos, have to think about it

        $statement = DataBaseConnection::executeStatement($query);
        $photos = $statement->fetchAll();
        return [true, $photos];
    }

    public static function requireCompilationDetails(int $compilationID): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = "SELECT name, description FROM Compilations WHERE compilationID=$compilationID";

        $statement = DataBaseConnection::executeStatement($query);
        $exhibitions = $statement->fetchAll();
        return [true, $exhibitions[0]];
    }

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

        $query = "SELECT name FROM Users";
        if ('latest' === $type) {
            $query .= " ORDER BY signUpDate DESC";
        } elseif ('compilation' === $type) {
            if (isset($args['compilationID'])) {
                $compilationID = $args['compilationID'];
                $query .= ''; // TODO
            }
        } elseif ('best' === $type) {
            $query .= ''; // TODO
        }
        $query .= " LIMIT $quantity";

        $statement = DataBaseConnection::executeStatement($query);
        $users = $statement->fetchAll();
        return [true, $users];
    }
}