<?php

namespace HealthChecker\Service;

abstract class AbstractService
{
    protected $config;
    protected $name;
    protected $assertions;
    protected $errors;

    public function __construct($config)
    {
        $this->validateConfig($config);

        $this->config = $config;

        $this->validateRequiredParams();

        $this->name = $config['name'];
        $this->assertions = $config['assertions'];
    }

    public function validateConfig($config)
    {
        if ( ! isset($config['name'])) {
            throw new Exception("Name is required in service definition");
        }

        if ( ! isset($config['assertions'])) {
            throw new Exception(sprintf("At least one assertion is required in service '%s' definition", $config['name']));
        }
    }

    public function check()
    {
        $errors = [];

        foreach ($this->assertions as $name => $params) {
            $assertMethodName = 'assert' . str_replace(' ', '', ucwords(str_replace('_', ' ', $name)));
            if ( ! is_callable([$this, $assertMethodName])) {
                throw new Exception(sprintf("Method %s doesn't exist for assertion '%s'", $assertMethodName, $name));
            }

            if ( ! call_user_func([$this, $assertMethodName], $params)) {
                $errors[] = [
                    'assertion' => [
                        $name => $params
                    ],
                    'message' => $this->getAssertionError($assertMethodName)
                ];
            }
        }

        return $errors;
    }

    protected function getAssertionError($assertMethodName)
    {
        return $this->errors[$assertMethodName];
    }

    abstract public function validateRequiredParams();
    abstract public function init();

}