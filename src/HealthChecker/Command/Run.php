<?php

namespace HealthChecker\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Run extends Command
{
    protected function configure()
    {
        $this->setName('run')
            ->setDefinition(array(
                    new InputArgument('config-file', InputArgument::OPTIONAL, 'Path to config file', 'config.yml'),
                    new InputOption('--help', '-h', InputOption::VALUE_NONE, 'Print command help and list of available assertions'),
                    new InputOption('--test', '-t', InputOption::VALUE_NONE, "Don't run, just test the configuration file"),
                    new InputOption('--output', '-o', InputOption::VALUE_OPTIONAL, 'Set format of output', 'tsv')
                ))
             ->setDescription('Runs health checks defined in config');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $args = $input->getArguments();
        $options = $input->getOptions();

        $output->writeln(print_r([
                    'Arguments' => $args,
                    'Options' => $options
                ], true));
    }
}