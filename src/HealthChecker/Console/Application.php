<?php

namespace HealthChecker\Console;

use HealthChecker\Console\Command;
use Symfony\Component\Console\Application as ConsoleApplication;
use Symfony\Component\Console\Input\InputDefinition;
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

    protected function getDefaultInputDefinition()
    {
        return new InputDefinition();
    }

    protected function getDefaultCommands()
    {
        return [
            new Command\HelpCommand(),
            new Command\RunCommand()
        ];
    }

    public function getDefinition()
    {
        $inputDefinition = parent::getDefinition();

        $inputDefinition->setArguments();

        return $inputDefinition;
    }
}