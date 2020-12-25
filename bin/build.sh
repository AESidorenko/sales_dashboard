#!/bin/bash

set -e

# todo: remove -d (delete database) option
php src/Command/Database/create-database.php -d
php src/Command/Database/create-schema.php
php src/Command/Database/load-data-fixtures.php