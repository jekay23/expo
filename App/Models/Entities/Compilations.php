<?php

namespace Expo\App\Models\Entities;

use Exception;
use Expo\App\Http\Controllers\Authentication;
use Expo\App\Models\QueryObject as QO;
use Expo\App\Models\Entity;

class Compilations extends Entity
{
    /**
     * @throws Exception
     */
    public static function getCompilationDetails(int $compilationID): array
    {
        self::prepareExecution();
        $query = QO::select()->table('Compilations')->columns('name', 'description');
        $query->where(['compilationID', $compilationID], ['isHidden', '0']);
        $compilations = self::executeQuery($query);
        return $compilations[0] ?? [];
    }

    /**
     * @throws Exception
     */
    public static function getCurrentExhibitionId(): int
    {
        self::prepareExecution();
        $query = QO::select()->table('Compilations')->columns(QO::max('exhibitNumber', 'maxExhibitNumber'));
        $result = self::executeQuery($query);
        return $result[0]['maxExhibitNumber'];
    }

    /**
     * @throws Exception
     */
    public static function getNextExhibitNumber(): int
    {
        return self::getCurrentExhibitionId() + 1;
    }

    /**
     * Get all compilations in admin app
     * @throws Exception
     */
    public static function getCompilations(): array
    {
        self::prepareExecution();
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
    public static function getTextFields(string $type, int $quantity, array $args = null): array
    {
        self::prepareExecution();
        if ('filters' === $type) {
            $filters = [
                ['name' => 'По дате публикации', 'href' => ''],
                ['name' => 'По полярности', 'href' => ''],
                ['name' => 'По выставкам', 'href' => '']
            ];
            return [true, $filters];
        }
        self::prepareExecution();
        $users = Users::getProfileNamesAndIds($type, $quantity, $args);
        return [true, $users];
    }

    /**
     * @throws Exception
     */
    public static function create(int $createdBy)
    {
        self::prepareExecution();
        $query = QO::insert()->table('Compilations')->columns('name', 'createdBy', 'isHidden');
        $query->values('Название подборки', $createdBy, 1);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function addCompilationItem(int $compilationID, int $photoID)
    {
        self::prepareExecution();
        $query = QO::insert()->table('CompilationItems')->columns('compilationID', 'photoID', 'addedBy');
        $query->values($compilationID, $photoID, Authentication::getUserIdFromCookie());
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function removeCompilationItem($compilationID, $photoID)
    {
        self::prepareExecution();
        $query = QO::delete()->table('CompilationItems');
        $query->where(['compilationID', $compilationID], ['photoID', $photoID]);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function updateString(int $compilationID, string $field, string $value)
    {
        self::prepareExecution();
        $query = QO::update()->table('Compilations');
        $query->columns($field)->values($value)->where(['compilationID', $compilationID]);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function updateBool(int $compilationID, string $field, bool $value)
    {
        self::prepareExecution();
        $query = QO::update()->table('Compilations')->columns($field)->values(($value ? 1 : 0));
        $query->where(['compilationID', $compilationID]);
        self::executeQuery($query, false);
    }

    /**
     * @throws Exception
     */
    public static function updateExhibit(int $compilationID, string $field, bool $value, int $exhibitNumber = 0)
    {
        self::prepareExecution();

        $query = QO::update()->table('Compilations')->columns($field)->values(($value ? 1 : 0));
        $query->where(['compilationID', $compilationID]);
        self::executeQuery($query, false);

        $query = QO::update()->table('Compilations')->columns('exhibitNumber');
        $query->values($exhibitNumber ?: 'NULL')->where(['compilationID', $compilationID]);
        self::executeQuery($query, false);
    }
}
