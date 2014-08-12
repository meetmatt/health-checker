<?php

namespace HealthChecker\Service;

abstract class ServiceFactory
{
    public static function getFactory($type)
    {
        switch (strtolower($type)) {
            case 'http':
                return new HttpServiceFactory();

            case 'db':
                return new DbServiceFactory();

            case 'redis':
                return new RedisServiceFactory();

            default:
                throw new Exception(sprintf("Unknown service type '%s'", $type));
        }
    }
}