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

        if ('latest' === $type) {
            $query->orderBy(['uploadTime', 'DESC']);
        } elseif ('compilation' === $type) {
            if (isset($args['compilationID'])) {
                $compilationID = $args['compilationID'];
                $query->join('RIGHT', 'CompilationItems', 'photoID');
                $query->where(['compilationID', $compilationID]);
            }
        } elseif ('best' === $type) {
            $query->addColumns(QO::count('userID', 'likes'), 'uploadTime');
            $query->join('RIGHT', 'Likes', 'photoID');
            $query->groupBy('photoID')->orderBy(['likes', 'DESC']);
        }
        $query->limit($quantity);

        // TODO: now if none of the conditions are met, the query just asks for $quantity photos, have to think about it
        ob_start();
        echo $query;
        $querySting = ob_get_clean();
        $statement = DataBaseConnection::executeStatement($querySting);
        $photos = $statement->fetchAll();
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

        ob_start();
        echo $query;
        $querySting = ob_get_clean();

        $statement = DataBaseConnection::executeStatement($querySting);
        $exhibitions = $statement->fetchAll();
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

        if ('latest' === $type) {
            $query->orderBy(['signUpDate', 'DESC']);
        } elseif ('compilation' === $type) {
            if (isset($args['compilationID'])) {
                $compilationID = $args['compilationID'];
                $query .= ''; // TODO
            }
        } elseif ('best' === $type) {
            $query .= ''; // TODO
        }
        $query->limit($quantity);

        ob_start();
        echo $query;
        $querySting = ob_get_clean();

        $statement = DataBaseConnection::executeStatement($querySting);
        $users = $statement->fetchAll();
        return [true, $users];
    }
}