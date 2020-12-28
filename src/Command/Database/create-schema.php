<?php

use App\Platform\Database\DatabaseConnectionFactory;

include_once __DIR__ . '/../bootstrap.php';

try {
    $db = DatabaseConnectionFactory::createDatabaseConnection($configManager->get('dbConnectionParameters'));

    $createCustomerTableQuery =
        'CREATE TABLE IF NOT EXISTS customer (
            id INT NOT NULL AUTO_INCREMENT,
            firstname VARCHAR(50) NULL,
            lastname VARCHAR(50) NULL,
            email VARCHAR(320) NULL,
            PRIMARY KEY (id))';

    $createOrderTableQuery =
        'CREATE TABLE IF NOT EXISTS `order` (
            id INT NOT NULL AUTO_INCREMENT,
            purchase_date DATE NOT NULL,
            country VARCHAR(2) NULL,
            device VARCHAR(255) NULL,
            customer_id INT,
            PRIMARY KEY (id),
            FOREIGN KEY (customer_id) REFERENCES customer(id),
            INDEX (purchase_date)) ENGINE=INNODB';

    $createOrderItemTableQuery =
        'CREATE TABLE IF NOT EXISTS order_item (
            id INT NOT NULL AUTO_INCREMENT,
            order_id INT NOT NULL,
            ean VARCHAR(13) NULL,
            quantity INT NOT NULL,
            price DECIMAL(10,2) NOT NULL,
            PRIMARY KEY (id),
            FOREIGN KEY (order_id) REFERENCES `order`(id)) ENGINE=INNODB';

    $db->query($createCustomerTableQuery);
    $db->query($createOrderTableQuery);
    $db->query($createOrderItemTableQuery);
} catch (Exception $exception) {
    printf("Unable to create database table. Error message: %s" . PHP_EOL, $exception->getMessage());
    exit(1);
}
