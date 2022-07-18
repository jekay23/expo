<?php

namespace Expo\App\Models;

use Exception;

class QueryConverter
{
    public static function composeSelect(
        string $table,
        array $columns,
        string $where,
        string $groupBy,
        array $orderBy,
        int $limit,
        int $offset,
        int $numOfJoins = 0
    ): string {
        $columnString = implode(', ', $columns);
        $queryString = "SELECT $columnString FROM $table";
        if (!empty($where)) {
            $queryString .= " WHERE $where";
        }
        if (!empty($groupBy)) {
            if ($numOfJoins > 0) {
                $groupBy = "TL$numOfJoins.$groupBy";
            }
            $queryString .= " GROUP BY $groupBy";
        }
        if (!empty($orderBy)) {
            if ($numOfJoins > 0) {
                foreach ($orderBy as &$order) {
                    $order = "TL$numOfJoins.$order";
                }
            }
            $queryString .= ' ORDER BY ' . implode(', ', $orderBy);
        }
        if (!empty($limit)) {
            $queryString .= " LIMIT $limit";
        }
        if (!empty($offset)) {
            $queryString .= " OFFSET $offset";
        }
        $queryString .= ';';
        return $queryString;
    }

    public static function composeInsert(
        string $table,
        array $columns,
        array $values
    ): string {
        $columnString = implode(', ', $columns);
        $valueString = implode(', ', $values);
        $queryString = "INSERT INTO $table ($columnString) VALUES ($valueString);";
        return $queryString;
    }

    public static function composeUpdate(
        string $table,
        array $columns,
        array $values,
        string $where
    ): string {
        $queryString = "UPDATE $table SET $columns[0] = $values[0]";
        if (!empty($where)) {
            $queryString .= " WHERE $where";
        }
        $queryString .= ';';
        return $queryString;
    }

    public static function composeDelete(string $table, string $where): string
    {
        $queryString = "DELETE FROM $table WHERE $where";
        $queryString .= ';';
        return $queryString;
    }
}
