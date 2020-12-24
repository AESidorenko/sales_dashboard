<?php

namespace App\Platform\Database\ObjectMapper;

use App\Platform\Database\DatabaseConnectionInterface;
use EmptyIterator;
use Iterator;

abstract class AbstractRepository
{
    protected static DatabaseConnectionInterface $connection;
    protected static string                      $entityClassname;
    protected static string                      $tablename;

    public function __construct(DatabaseConnectionInterface $connection, string $entityClassname)
    {
        self::$connection      = $connection;
        self::$entityClassname = $entityClassname;
        self::$tablename       = $entityClassname::getTableName();
    }

    public function findOne(string $id): ?EntityInterface
    {
        $result = self::query("SELECT * FROM %s WHERE id=%s", [self::$tablename, $id]);
        if (iterator_count($result) === 0) {
            return null;
        }

        return $result->current();
    }

    public function findOneBy(string $condition): ?EntityInterface
    {
        $result = self::query("SELECT * FROM %s WHERE %s", [self::$tablename, $condition]);
        if (iterator_count($result) === 0) {
            return null;
        }

        return $result->current();
    }

    public function findBy(string $condition = 'true'): Iterator
    {
        $result = self::query("SELECT * FROM %s WHERE %s", [self::$tablename, $condition]);
        if (iterator_count($result) === 0) {
            return new EmptyIterator();
        }

        return $result;
    }

    public function findAll(): Iterator
    {
        $result = self::query("SELECT * FROM %s", [self::$tablename]);
        if (iterator_count($result) === 0) {
            return new EmptyIterator();
        }

        return $result;
    }

    protected static function query(string $sql, array $params, bool $map = true): Iterator
    {
        return self::$connection->query($sql, $params, $map ? self::$entityClassname : 'stdClass');
    }

    protected static function queryArrayResult(string $sql, array $params, bool $map = true): array
    {
        $result = self::query($sql, $params, $map);

        return iterator_to_array($result);
    }
}