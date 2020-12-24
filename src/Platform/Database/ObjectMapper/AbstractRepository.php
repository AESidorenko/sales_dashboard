<?php

namespace App\Platform\Database\ObjectMapper;

use App\Platform\Database\DatabaseConnectionInterface;
use EmptyIterator;
use Iterator;

abstract class AbstractRepository
{
    protected static DatabaseConnectionInterface $connection;
    protected string                             $entityClassname;
    protected string                             $tablename;

    public function __construct(DatabaseConnectionInterface $connection, string $entityClassname)
    {
        self::$connection      = $connection;
        $this->entityClassname = $entityClassname;
        $this->tablename       = $entityClassname::getTableName();
    }

    public function findOne(string $id): ?EntityInterface
    {
        $result = $this->query("SELECT * FROM `%s` WHERE id=%s", [$this->tablename, $id]);
        if (iterator_count($result) === 0) {
            return null;
        }

        return $result->current();
    }

    public function findOneBy(string $condition): ?EntityInterface
    {
        $result = $this->query("SELECT * FROM `%s` WHERE %s", [$this->tablename, $condition]);
        if (iterator_count($result) === 0) {
            return null;
        }

        return $result->current();
    }

    public function findBy(string $condition = 'true'): Iterator
    {
        $result = $this->query("SELECT * FROM `%s` WHERE %s", [$this->tablename, $condition]);
        if (iterator_count($result) === 0) {
            return new EmptyIterator();
        }

        return $result;
    }

    public function findAll(): Iterator
    {
        $result = $this->query("SELECT * FROM `%s`", [$this->tablename]);
        if (iterator_count($result) === 0) {
            return new EmptyIterator();
        }

        return $result;
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