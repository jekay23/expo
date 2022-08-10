<?php

namespace Expo\App\Models;

use Exception;

class Entity
{
    /**
     * @throws Exception
     */
    protected static function prepareExecution()
    {
        if (!DataBaseConnection::makeSureConnectionIsOpen()) {
            throw new Exception('Unable to connect to server. Please try again later or contact support.', 0);
        }
    }

    protected static function executeQuery(QueryObject $query, bool $yields = true)
    {
        ob_start();
        echo $query;
        $querySting = ob_get_clean();

        $statement = DataBaseConnection::executeQuery($querySting);
        if ($yields) {
            return $statement->fetchAll();
        } else {
            return [];
        }
    }
}
