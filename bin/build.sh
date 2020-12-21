#!/bin/bash

set -e

php src/Command/Database/create-database.php
php src/Command/Database/create-schema.php
