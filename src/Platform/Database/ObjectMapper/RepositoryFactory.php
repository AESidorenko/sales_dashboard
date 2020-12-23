<?php

namespace App\Platform\Database\ObjectMapper;

use App\Platform\Database\DatabaseConnectionInterface;
use ReflectionClass;
use RuntimeException;

class RepositoryFactory
{
    /**
     * @var DatabaseConnectionInterface
     */
    private static DatabaseConnectionInterface $connection;

    function __construct(DatabaseConnectionInterface $connection)
    {
        self::$connection = $connection;
    }

    public static function createRepository(string $repositoryEntityClassname)
    {
        $entityClassname = new ReflectionClass($repositoryEntityClassname);
        if (!$entityClassname->implementsInterface(EntityInterface::class)) {
            throw new RuntimeException(sprintf('Entity class %s must implement %s', $repositoryEntityClassname, EntityInterface::class));
        }

        $repositoryClassname = 'App\Repository\\' . $entityClassname->getName() . 'Repository';
        if (!class_exists($repositoryClassname)) {
            throw new RuntimeException(sprintf('Repository class %s doesn\'t exist', $repositoryClassname));
        }

        return new $repositoryClassname(self::$connection, $repositoryEntityClassname);
    }
}