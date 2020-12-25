<?php

use App\Platform\Database\DatabaseConnectionFactory;

include_once __DIR__ . '/../bootstrap.php';

try {
    $dbConnectionParameters = $configManager->get('dbConnectionParameters');

    $db = DatabaseConnectionFactory::createDatabaseConnection($dbConnectionParameters, false);

    $options = getopt('d');
    if (array_key_exists('d', $options)) {
        $db->query('DROP DATABASE IF EXISTS `%s`', [$dbConnectionParameters['dbSchema']]);
    }

    $db->query('CREATE DATABASE IF NOT EXISTS `%s`', [$dbConnectionParameters['dbSchema']]);
} catch (Exception $exception) {
    printf("Unable to create database. Error message: %s" . PHP_EOL, $exception->getMessage());
    exit(1);
}
