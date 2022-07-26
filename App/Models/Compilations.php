<?php

namespace Expo\App\Models;

use Expo\App\Models\QueryObject as QO;

class Compilations extends QueryBuilder
{
    /**
     * @throws \Exception
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
}
