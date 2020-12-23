<?php

namespace App\Platform\Database\ObjectMapper;

use App\Helper\StringNormalizerHelper;
use App\Platform\Database\DatabaseConnectionInterface;

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
        if (count($result) === 0) {
            return null;
        }

        return $result[0];
    }

    public function findOneBy(string $condition): ?EntityInterface
    {
        $result = self::query("SELECT * FROM %s WHERE %s", [self::$tablename, $condition]);
        if (count($result) === 0) {
            return null;
        }

        return $result[0];
    }

    public function findBy(string $condition = 'true'): array
    {
        $result = self::query("SELECT * FROM %s WHERE %s", [self::$tablename, $condition]);
        if (count($result) === 0) {
            return [];
        }

        return $result;
    }

    public function findAll(): array
    {
        $result = self::query("SELECT * FROM %s", [self::$tablename]);
        if (count($result) === 0) {
            return [];
        }

        return $result;
    }

    protected static function query(string $sql, array $params, bool $map = true)
    {
        $result = self::$connection->query($sql, $params);

        $arrayResult = [];
        while ($row = mysqli_fetch_assoc($result)) {
            if ($map) {
                $object = new self::$entityClassname();
                foreach ($row as $key => $value) {
                    $setter = StringNormalizerHelper::toCamelCase('set_' . $key);
                    $object->$setter($value);
                }
                $arrayResult[] = $object;
            } else {
                $arrayResult[] = (object)$row;
            }
        }

        mysqli_free_result($result);

        return $arrayResult;
    }
}