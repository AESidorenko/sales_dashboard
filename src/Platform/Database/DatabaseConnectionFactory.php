<?php

namespace App\Platform\Database;

use App\Platform\Component\ConfigurationManager;
use RuntimeException;

class DatabaseConnectionFactory
{
    public const DB_TYPE_MYSQLI = 'mysqli';

    private static $instances = [];

    public static function get(string $dbType, ?string $dbName = null): DatabaseConnectionInterface
    {
        $configManager = ConfigurationManager::loadConfiguration();

        switch ($dbType) {
            case self::DB_TYPE_MYSQLI:
                $createConnectionObject = fn() => new MysqliConnection($configManager->get('dbHost'), $configManager->get('dbUsername'),
                    $configManager->get('dbPassword'), $dbName);

                break;
            default:
                throw new RuntimeException(sprintf('Invalid database connection type: %s', $dbType));
        }

        if (!is_callable($createConnectionObject)) {
            throw new RuntimeException(sprintf('Invalid connection producer for type: %s', $dbType));
        }

        $dbConnectionObject = self::$instances[$dbType] ?? $createConnectionObject();
        if (!$dbConnectionObject instanceof DatabaseConnectionInterface) {
            throw new RuntimeException(sprintf('Invalid connection producer for type: %s', $dbType));
        }

        self::$instances[$dbType] = $dbConnectionObject;

        return $dbConnectionObject;
    }
}