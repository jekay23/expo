<?php

namespace Expo\App\Models;

class QueryObject
{
    private string $query;

    public function __construct()
    {
        $this->query = '';
    }

    public function select(string ...$columns)
    {
        $this->query .= 'SELECT';
        if (isset($columns)) {
            foreach ($columns as $column) {
                $this->query .= " $column,";
            }
            $this->query = substr($this->query, 0, -1);
        } else {
            $this->query .= ' *';
        }
    }

    public function from(string $table)
    {
        $this->query .= " FROM $table";
    }
}
