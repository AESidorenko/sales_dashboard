#!/bin/bash

set -e

echo "Create database schema"
php src/Command/Database/create-schema.php

echo "Load data fixtures. It may take some time..."
php src/Command/Database/load-data-fixtures.php

echo "Project is ready to start. Please, open it in your browser."