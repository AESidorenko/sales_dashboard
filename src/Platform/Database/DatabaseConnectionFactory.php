<?php

namespace App\Platform\Database;

use RuntimeException;

class DatabaseConnectionFactory
{
    public const DB_TYPE_MYSQLI = 'mysqli';

    private static $instances = [];

    public static function createDatabaseConnection(array $connectionParameters, bool $selectSchema = true): DatabaseConnectionInterface
    {
        switch ($connectionParameters['dbType']) {
            case self::DB_TYPE_MYSQLI:
                $createConnectionObject =
                    fn(): DatabaseConnectionInterface => new MysqliConnection(
                        $connectionParameters['dbHost'],
                        $connectionParameters['dbUsername'],
                        $connectionParameters['dbPassword'],
                        $selectSchema ? $connectionParameters['dbSchema'] : null
                    );

                break;
            default:
                throw new RuntimeException(sprintf('Invalid database connection type: %s', $connectionParameters['dbType']));
        }

        if (!is_callable($createConnectionObject)) {
            throw new RuntimeException(sprintf('Invalid connection producer for type: %s', $connectionParameters['dbType']));
        }

        $dbConnectionObject = self::$instances[$connectionParameters['dbType']] ?? $createConnectionObject();
        if (!$dbConnectionObject instanceof DatabaseConnectionInterface) {
            throw new RuntimeException(sprintf('Invalid connection producer for type: %s', $connectionParameters['dbType']));
        }

        self::$instances[$connectionParameters['dbType']] = $dbConnectionObject;

        return $dbConnectionObject;
    }
}