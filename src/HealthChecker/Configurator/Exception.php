<?php

namespace HealthChecker\Configurator;

class Exception extends \Exception
{
    const CONFIG_NOT_FOUND = 1;
    const CONFIG_NOT_READABLE = 2;
    const CONFIG_NOT_VALID_YML = 3;
    const CONFIG_EMPTY = 4;
}