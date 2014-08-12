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
        if ( ! isset($config['name'])) {
            throw new Exception("Name is required in service definition");
        }

        $this->config = $config;

        $this->validateRequiredParams();

        $this->name = $config['name'];
        $this->assertions = array_merge($this->getDefaultAssertions(), isset($config['assertions']) ? $config['assertions'] : []);
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
                $message = $this->getAssertionError($assertMethodName);
                if ( ! $message) {
                    continue;
                }

                $errors[] = [
                    'assertion' => [
                        $name => $params
                    ],
                    'message' => $message
                ];
            }
        }

        return $errors;
    }

    protected function getAssertionError($assertMethodName)
    {
        return isset($this->errors[$assertMethodName]) ? $this->errors[$assertMethodName] : false;
    }

    abstract public function validateRequiredParams();
    abstract public function getDefaultAssertions();
    abstract public function init();

}