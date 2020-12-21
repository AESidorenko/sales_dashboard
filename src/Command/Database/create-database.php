<?php

use App\Platform\Database\DatabaseConnectionFactory;

include_once __DIR__ . '/../bootstrap.php';

try {
    $db     = DatabaseConnectionFactory::get($configManager->get('dbType'));
    $sql    = 'CREATE DATABASE IF NOT EXISTS `%s`';
    $params = [$configManager->get('dbSchema')];
    $db->query($sql, $params);
} catch (Exception $exception) {
    printf("Unable to create database. Error message: %s" . PHP_EOL, $exception->getMessage());
    exit(1);
}
