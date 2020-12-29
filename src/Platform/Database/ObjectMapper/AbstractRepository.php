<?php

namespace App\Platform\Database\ObjectMapper;

use App\Platform\Database\DatabaseConnectionInterface;
use Iterator;

class AbstractRepository
{
    protected static DatabaseConnectionInterface $connection;
    protected string                             $entityClassname;
    protected string                             $tablename;

    protected function __construct(DatabaseConnectionInterface $connection, string $entityClassname)
    {
        self::$connection = $connection;

        $this->entityClassname = $entityClassname;
        $this->tablename       = $entityClassname::getTableName();
    }

    protected function query(string $sql, array $params, bool $map = true): Iterator
    {
        return self::$connection->query($sql, $params, $map ? $this->entityClassname : 'stdClass');
    }

    protected function queryArrayResult(string $sql, array $params, bool $map = true): array
    {
        $result = $this->query($sql, $params, $map);

        return iterator_to_array($result);
    }
}