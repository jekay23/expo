<?php

namespace Expo\App\Models;

use Exception;
use Expo\App\Models\QueryObject as QO;

class Compilations extends QueryBuilder
{
    /**
     * @throws Exception
     */
    public static function getCompilationDetails(int $compilationID): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = QO::select()->table('Compilations')->columns('name', 'description');
        $query->where(['compilationID', $compilationID]);

        $compilations = self::executeQuery($query);
        return [true, $compilations[0]];
    }

    public static function getCurrentExhibition(): array
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            return [false, null];
        }

        $query = QO::select()->table('Compilations')->columns('compilationID');
        $query->orderBy(['exhibitNumber', 'DESC'])->limit(1);

        $compilations = self::executeQuery($query);
        return [true, $compilations[0]['compilationID']];
    }

    public static function getCompilations(): array
    {
        $query = QO::select()->table('Compilations');
        $query->columns(
            'compilationID',
            'name',
            'description',
            'creationTime',
            'isExhibit',
            'exhibitNumber',
            'isHidden'
        );

        return self::executeQuery($query);
    }

    public static function updateString(int $compilationID, string $field, string $value)
    {
        switch ($field) {
            case 'description':
                $field = 'description';
                break;
            case 'name':
                $field = 'name';
                break;
        }
        $query = QO::update()->table('Compilations');
        $query->columns($field)->values($value)->where(['compilationID', $compilationID]);

        self::executeQuery($query, false);
    }

    public static function updateBool(int $compilationID, string $field, bool $value)
    {
        $query = QO::update()->table('Compilations');
        $query->columns($field)->values(($value ? 1 : 0))->where(['compilationID', $compilationID]);

        self::executeQuery($query, false);
    }
}
