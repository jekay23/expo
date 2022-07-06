<?php

namespace Expo\App\Models;

use Exception;
use Expo\App\Models\QueryConverter as QC;

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
    private array $values;

    private function __construct(string $action)
    {
        $this->action = $action;
        $this->query = '';
        $this->table = '';
        $this->columns = ['*'];
        $this->where = '';
        $this->groupBy = '';
        $this->orderBy = array();
        $this->limit = 0;
        $this->offset = 0;
        $this->numOfJoins = 0;
        $this->grouppable = false;
        $this->values = array();
    }

    public function __toString(): string
    {
        if ('select' == $this->action) {
            $this->query = QC::composeSelect(
                $this->table,
                $this->columns,
                $this->where,
                $this->groupBy,
                $this->orderBy,
                $this->limit,
                $this->offset,
                $this->numOfJoins
            );
        } elseif ('insert' == $this->action) {
            $this->query = QC::composeInsert(
                $this->table,
                $this->columns,
                $this->values
            );
        } elseif ('update' == $this->action) {
            $this->query = QC::composeUpdate(
                $this->table,
                $this->columns,
                $this->values,
                $this->where
            );
        }
        return $this->query;
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
        if (!empty($alias)) {
            return "COUNT($column) AS $alias";
        } else {
            return "COUNT($column)";
        }
    }

    /**
     * @throws Exception
     */
    public function table($table): QueryObject
    {
        if (!empty($this->table)) {
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

    /**
     * @throws Exception
     */
    public function join(string $type, $table, ...$conditions): QueryObject
    {
        $types = ['RIGHT', 'LEFT', 'INNER', 'FULL'];
        if (!in_array($type, $types)) {
            throw new Exception('Incorrect query formation: unknown join type');
        }
        if (!!empty($this->table)) {
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
        if (!empty($conditions)) {
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

    /**
     * @throws Exception
     */
    public function columns(string ...$columns): QueryObject
    {
        if ('update' == $this->action && count($columns) != 1) {
            throw new Exception('Incorrect query formation: selecting more than 1 column for update');
        }
        foreach ($columns as $column) {
            if ('COUNT' == substr($column, 0, 5)) {
                $this->grouppable = true;
                break;
            }
        }
        $this->columns = $columns;
        return $this;
    }

    public function addColumns(string ...$columns): QueryObject
    {
        if (!$this->grouppable) {
            foreach ($columns as $column) {
                if ('COUNT' == substr($column, 0, 5)) {
                    $this->grouppable = true;
                    break;
                }
            }
        }
        $this->columns = array_merge($this->columns, $columns);
        return $this;
    }

    /**
     * @throws Exception
     */
    public function where(array ...$conditions): QueryObject
    {
        if (!empty($this->where)) {
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

    /**
     * @throws Exception
     */
    public function limit(int $limit): QueryObject
    {
        if (!empty($this->limit)) {
            throw new Exception('Incorrect query formation: rewriting the LIMIT statement');
        }
        if ($limit > 0) {
            $this->limit = $limit;
        } else {
            throw new Exception('Incorrect query formation: negative limit setting');
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    public function offset(int $offset): QueryObject
    {
        if (!empty($this->offset)) {
            throw new Exception('Incorrect query formation: rewriting the OFFSET statement');
        }
        if ($offset > 0) {
            $this->offset = $offset;
        } else {
            throw new Exception('Incorrect query formation: negative offset setting');
        }
        return $this;
    }

    /**
     * @throws Exception
     */
    public function groupBy(string $column): QueryObject
    {
        if (!$this->grouppable) {
            throw new Exception('Incorrect query formation: grouping ungrouppable statement');
        }
        if (!empty($this->groupBy)) {
            throw new Exception('Incorrect query formation: rewriting the GROUP BY statement');
        }
        $this->groupBy = $column;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function orderBy(array ...$orders): QueryObject
    {
        if (!empty($this->orderBy)) {
            throw new Exception('Incorrect query formation: rewriting the ORDER BY statement');
        }
        $orderBy = array();
        $orderTypes = ['DESC', 'ASC'];
        foreach ($orders as $order) {
            if (!empty($order[1])) {
                if (!in_array($order[1], $orderTypes)) {
                    throw new Exception('Incorrect query formation: unknown order type');
                }
            }
            $orderBy[] = implode(' ', $order);
        }

        $this->orderBy = $orderBy;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function values(array $values): QueryObject
    {
        if ('select' == $this->action) {
            throw new Exception('Incorrect query formation: using VALUES statement while selecting');
        }
        if ('update' == $this->action && count($values) != 1) {
            throw new Exception('Incorrect query formation: selecting more than 1 value for update');
        }
        if (count($this->columns) != count($values)) {
            throw new Exception('Incorrect query formation: number of inserted values doesn`t match number of columns');
        }
        $this->values = $values;
        return $this;
    }
}
