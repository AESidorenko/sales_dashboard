<?php
declare(strict_types=1);

namespace App\Platform\Component;

use App\Platform\DataStructure\ImmutableCollection;
use RuntimeException;

class ConfigurationManager
{
    private static ConfigurationManager $instance;

    private string               $rootDir = '';
    private ImmutableCollection  $config;

    public function __construct()
    {
        $this->rootDir = realpath(__DIR__ . '/../../..');

        $configFilename = $this->rootDir . '/config.php';
        if (!is_readable($configFilename)) {
            throw new RuntimeException(sprintf('Configuration file %s not found', $configFilename));
        }

        $config = include($configFilename);
        if (!is_array($config)) {
            throw new RuntimeException(sprintf('Invalid configuration file %s content', $configFilename));
        }
        $this->config = new ImmutableCollection($config);
    }

    public function get(string $paramName)
    {
        return $this->config->get($paramName);
    }

    public function has(string $paramName): bool
    {
        return $this->config->has($paramName);
    }
}