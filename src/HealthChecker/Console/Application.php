<?php

namespace HealthChecker\Console;

use HealthChecker\Command\Run;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputInterface;

class Application extends ConsoleApplication
{
    const NAME = 'Health Checker';
    const VERSION = '0.0.1';

    public function __construct()
    {
        parent::__construct(self::NAME, self::VERSION);
    }

    protected function getCommandName(InputInterface $input)
    {
        return 'run';
    }

    protected function getDefaultCommands()
    {
        return [
            new Run()
        ];
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();

        // clear out the normal first argument, which is the command name
        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}