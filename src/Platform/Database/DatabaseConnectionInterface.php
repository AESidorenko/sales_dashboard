<?php

namespace App\Platform\Database;

interface DatabaseConnectionInterface
{
    function query(string $sql, array $params = []);
}