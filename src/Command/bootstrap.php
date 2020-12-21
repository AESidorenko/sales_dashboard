<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use App\Platform\Component\ConfigurationManager;

if (defined("ENV")) {
    exit(0);
}

$configManager = ConfigurationManager::loadConfiguration();