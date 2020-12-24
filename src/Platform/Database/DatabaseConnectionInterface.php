<?php

namespace App\Platform\Database;

use Iterator;

interface DatabaseConnectionInterface
{
    function query(string $sql, array $params = [], string $className = 'stdClass'): Iterator;

    function getLastInsertId();
}