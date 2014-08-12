<?php

namespace HealthChecker\Service;

class ServiceFactory
{
    public static function create(array $config)
    {
        $type = $config['type'];

        switch (strtolower($type)) {
            case 'http':
                $service = new HttpService($config);
                break;

            /*
            case 'db':
                $service = new DbService($name);
                break;

            case 'redis':
                $service = new RedisService($name);
                break;
            */

            default:
                throw new Exception(sprintf("Unknown service type '%s'", $type));
        }

        $service->init($config);

        return $service;
    }
}