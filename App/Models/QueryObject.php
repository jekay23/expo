<?php

namespace Expo\App\Models;

use Exception;

class QueryObject
{
    private string $action;
    private string $query;
    private string $table;
    private array $columns;
    private string $where;
    private string $groupBy;
    private array $orderBy;
    private int $limit;
    private int $offset;
    private int $numOfJoins;
    private bool $grouppable;

    private function __construct(string $action)
    {
        $this->action = $action;
        $this->query = '';
        $this->table = null;
        $this->columns = ['*'];
        $this->where = null;
        $this->groupBy = null;
        $this->orderBy = null;
        $this->limit = null;
        $this->offset = null;
        $this->numOfJoins = 0;
        $this->grouppable = false;
    }

    public function __toString(): string
    {
        $actions = [
            'select' => 'QueryObject::composeSelect',
            'insert' => 'QueryObject::composeInsert',
            'update' => 'QueryObject::composeUpdate'
        ];
        if (isset($actions[$this->action])) {
            $this->query = $actions[$this->action](
                $this->table,
                $this->columns,
                $this->where,
                $this->groupBy,
                $this->orderBy,
                $this->limit,
                $this->offset
            );
        }
        return $this->query;
    }

    public function getAction(): string
    {
        return $this->action;
    }

    public function getNumOfJoins(): string
    {
        return $this->numOfJoins;
    }

    public static function select(): QueryObject
    {
        return new QueryObject('select');
    }

    public static function insert(): QueryObject
    {
        return new QueryObject('insert');
    }

    public static function update(): QueryObject
    {
        return new QueryObject('update');
    }

    public static function count(string $column = '*', string $alias = null): string
    {
        if (isset($alias)) {
            return "COUNT($column) AS $alias";
        } else {
            return "COUNT($column)";
        }
    }

    private static function composeSelect(
        string $table,
        array $columns,
        $where,
        $groupBy,
        $orderBy,
        $limit,
        $offset
    ): string {
        $queryString = 'SELECT ';
        $queryString .= implode(', ', $columns);
        $queryString .= " FROM $table";
        if (isset($where)) {
            $queryString .= " WHERE $where";
        }
        if (isset($groupBy)) {
            $queryString .= " GROUP BY $groupBy";
        }
        if (isset($orderBy)) {
            $queryString .= ' ' . implode(', ', $orderBy);
        }
        if (isset($limit)) {
            $queryString .= " LIMIT $limit";
        }
        if (isset($offset)) {
            $queryString .= " OFFSET $offset";
        }
        $queryString .= ';';
        return $queryString;
    }

    public function table($table): QueryObject
    {
        if (isset($this->table)) {
            throw new Exception('Incorrect query formation: using two tables without joining them');
        }
        if ('string' == gettype($table)) {
            $this->table = $table;
        } elseif ('QueryObject' == gettype($table)) {
            if ('select' == $table->action) {
                ob_start();
                echo $table;
                $tableString = ob_get_clean();
                $this->table = "($tableString)";
            } else {
                throw new Exception('Incorrect query formation: using composed table for other purpose than selection');
            }
        }
        return $this;
    }

    public function join(string $type, $table, ...$conditions): QueryObject
    {
        $types = ['RIGHT', 'LEFT', 'INNER', 'FULL'];
        if (!in_array($type, $types)) {
            throw new Exception('Incorrect query formation: unknown join type');
        }
        if (!isset($this->table)) {
            throw new Exception('Incorrect query formation: first table of the join hasn`t been specified');
        }
        if ('select' != $this->action) {
            throw new Exception('Incorrect query formation: using composed table for other purpose than selection');
        }
        if ('string' == gettype($table)) {
            $this->numOfJoins++;
            $this->table .= " AS TL$this->numOfJoins $type JOIN $table AS TR$this->numOfJoins";
        } elseif ('QueryObject' == gettype($table)) {
            if ('select' == $table->action) {
                ob_start();
                echo $table;
                $tableString = ob_get_clean();
                $this->numOfJoins = $table->getNumOfJoins() + 1;
                $this->table .= " AS TL$this->numOfJoins $type JOIN ($tableString) AS TR$this->numOfJoins";
            } else {
                throw new Exception('Incorrect query formation: using composed table for other purpose than selection');
            }
        }
        if (isset($conditions)) {
            if (1 == count($conditions)) {
                $conditions[1] = $conditions[0];
            }
            if (2 == count($conditions)) {
                $condition = "TL$this->numOfJoins.$conditions[0] = TR$this->numOfJoins.$conditions[1]";
            } else {
                throw new Exception('Incorrect query formation: too many conditions for joining');
            }
            $this->table .= " ON $condition";
        } else {
            throw new Exception('Incorrect query formation: join condition is not specified');
        }
        return $this;
    }

    public function columns(string ...$columns): QueryObject
    {
        foreach ($columns as $column) {
            if ('COUNT' == substr($column, 0, 5)) {
                $this->grouppable = true;
                break;
            }
        }
        $this->columns = $columns;
        return $this;
    }

    public function where(array ...$conditions): QueryObject
    {
        if (isset($this->where)) {
            throw new Exception('Incorrect query formation: rewriting the WHERE statement');
        }
        $conditionStrings = array();
        foreach ($conditions as $condition) {
            if (2 != count($condition)) {
                throw new Exception('Incorrect query formation: wrong where condition format');
            }
            $conditionStrings[] = "$condition[0] = $condition[1]";
        }
        $this->where = implode(' AND ', $conditionStrings);
        return $this;
    }

    public function limit(int $limit): QueryObject
    {
        if (isset($this->limit)) {
            throw new Exception('Incorrect query formation: rewriting the LIMIT statement');
        }
        if ($limit > 0) {
            $this->limit = $limit;
        } else {
            throw new Exception('Incorrect query formation: negative limit setting');
        }
        return $this;
    }

    public function offset(int $offset): QueryObject
    {
        if (isset($this->offset)) {
            throw new Exception('Incorrect query formation: rewriting the OFFSET statement');
        }
        if ($offset >= 0) {
            $this->offset = $offset;
        } else {
            throw new Exception('Incorrect query formation: negative offset setting');
        }
        return $this;
    }

    public function groupBy(string $column): QueryObject
    {
        if (!$this->grouppable) {
            throw new Exception('Incorrect query formation: grouping ungrouppable statement');
        }
        if (isset($this->groupBy)) {
            throw new Exception('Incorrect query formation: rewriting the GROUP BY statement');
        }
        $this->groupBy = $column;
        return $this;
    }

    public function orderBy(array ...$orders): QueryObject
    {
        if (isset($this->orderBy)) {
            throw new Exception('Incorrect query formation: rewriting the ORDER BY statement');
        }
        $orderBy = array();
        $orderTypes = ['DESC', 'ASC'];
        foreach ($orders as $order) {
            if (isset($order[1])) {
                if (!in_array($order[1], $orderTypes)) {
                    throw new Exception('Incorrect query formation: unknown order type');
                }
            }
            $orderBy[] = implode(' ', $order);
        }

        $this->orderBy = $orderBy;
        return $this;
    }
}
