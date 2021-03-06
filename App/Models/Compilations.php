<?php

namespace Expo\App\Models;

use Exception;
use Expo\App\Http\Controllers\Authentication;
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
        $query->where(['compilationID', $compilationID], ['isHidden', '0']);

        $compilations = self::executeQuery($query);
        if (!empty($compilations)) {
            return [true, $compilations[0]];
        } else {
            return [true, []];
        }
    }

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
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

    /**
     * @throws Exception
     */
    public static function updateString(int $compilationID, string $field, string $value)
    {
        $query = QO::update()->table('Compilations');
        $query->columns($field)->values($value)->where(['compilationID', $compilationID]);

        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function updateBool(int $compilationID, string $field, bool $value)
    {
        $query = QO::update()->table('Compilations')->columns($field)->values(($value ? 1 : 0));
        $query->where(['compilationID', $compilationID]);

        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function updateExhibit(int $compilationID, string $field, bool $value, int $exhibitNumber = 0)
    {
        $query = QO::update()->table('Compilations')->columns($field)->values(($value ? 1 : 0));
        $query->where(['compilationID', $compilationID]);
        self::executeQuery($query, false);

        $query = QO::update()->table('Compilations')->columns('exhibitNumber');
        $query->values($exhibitNumber ? $exhibitNumber : 'NULL')->where(['compilationID', $compilationID]);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function create(int $createdBy)
    {
        $query = QO::insert()->table('Compilations')->columns('name', 'createdBy', 'isHidden');
        $query->values('???????????????? ????????????????', $createdBy, 1);

        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function getNextExhibitNumber(): int
    {
        $query = QO::select()->table('Compilations')->columns(QO::max('exhibitNumber', 'maxExhibitNumber'));
        $result = self::executeQuery($query);
        return $result[0]['maxExhibitNumber'] + 1;
    }

    /**
     * @throws Exception
     */
    public static function addCompilationItem(int $compilationID, int $photoID)
    {
        $query = QO::insert()->table('CompilationItems')->columns('compilationID', 'photoID', 'addedBy');
        $query->values($compilationID, $photoID, Authentication::getUserIdFromCookie());

        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function removeCompilationItem($compilationID, $photoID)
    {
        $query = QO::delete()->table('CompilationItems');
        $query->where(['compilationID', $compilationID], ['photoID', $photoID]);

        self::executeQuery($query, false);
    }


}
