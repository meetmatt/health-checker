<?php

namespace HealthChecker\Configurator;

use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class Configurator
{
    protected $configFile;

    public function __construct($configFile)
    {
        $this->configFile = $configFile;
    }

    public function loadConfig()
    {
        if ( ! file_exists($this->configFile)) {
            throw new Exception(sprintf("There is no '%s' configuration file", $this->configFile), Exception::CONFIG_NOT_FOUND);
        }

        if ( ! is_readable($this->configFile)) {
            throw new Exception(sprintf("Configuration file '%s' is not readable", $this->configFile), Exception::CONFIG_NOT_READABLE);
        }

        try {
            $config = Yaml::parse($this->configFile);
        } catch (ParseException $e) {
            throw new Exception(sprintf("Configuration file '%s' is not a valid YAML file", $this->configFile), Exception::CONFIG_NOT_VALID_YML);
        }

        if (empty($config)) {
            throw new Exception(sprintf("Configuration file '%s' is empty", $this->configFile), Exception::CONFIG_EMPTY);
        }

        return $config;
    }
}