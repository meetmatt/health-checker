<?php

namespace HealthChecker\Console\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('run')
            ->setDefinition([
                    new InputArgument('config-file', InputArgument::OPTIONAL, 'Path to config file', 'config.yml'),
                    new InputOption('--test', '-t', InputOption::VALUE_NONE, "Don't run, just test the configuration file"),
                    new InputOption('--help', '-h', InputOption::VALUE_NONE, "Print command help and list of available assertions"),
                    new InputOption('--output', '-o', InputOption::VALUE_OPTIONAL, 'Set format of output', 'tsv')
                ])
            ->setHelp('Runs health checks defined in config.')
            ->setDescription('Runs health checks defined in config');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $config = $input->getArgument('config-file');
        $isTest = $input->getOption('test');

        $output->writeln([
                $isTest ? 'Test config' : 'Run checker',
                'Config: ' . $config,
                'Output format: '. $input->getOption('output')
            ]);
    }
}