<?php

namespace HealthChecker\Console\Command;

use HealthChecker\Configurator\Configurator;
use HealthChecker\Configurator\Exception;
use HealthChecker\Service\ServiceFactory;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class RunCommand extends Command
{
    protected $input;
    protected $output;

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
        $this->input = $input;
        $this->output = $output;

        $configFile = $input->getArgument('config-file');
        $testOnly = $input->getOption('test');

        $config = $this->loadConfig($configFile);

        $services = [];

        foreach ($config as $service) {
            $services[$service['name']] = ServiceFactory::create($service);
        }

        if ($testOnly) {
            exit();
        }

        $result = [];
        foreach ($services as $name => $service) {
            $result[$name] = $service->check();
        }

        if ($input->getOption('output') === 'json') {
            $this->printJson($result);
        } else {
            $this->printTsv($result);
        }

    }

    protected function printTsv($res)
    {
        $result = [];

        foreach ($res as $name => $errors) {
            if (empty($errors)) {
                $result[] = implode("\t", ['ok', $name, '']);
            } else {
                $result[] = implode("\t", ['fail', $name, $errors[0]['message']]);
            }
        }

        echo implode("\n", $result) . "\n";
    }

    protected function printJson($res)
    {
        $result = [];

        foreach ($res as $name => $errors) {
            if (empty($errors)) {
                $result[$name] = [
                    'status' => 'ok',
                    'details' => []
                ];
            } else {
                $result[$name] = [
                    'status' => 'fail',
                    'details' => $errors
                ];
            }
        }

        echo json_encode($result, JSON_PRETTY_PRINT) . "\n";
    }

    protected function loadConfig($configFile)
    {
        $configLoader = new Configurator($configFile);

        try {
            $config = $configLoader->loadConfig();

        } catch (Exception $e) {
            $this->output->writeln([
                    'Config test: <error>FAIL</error>',
                    '<error>' . $e->getMessage() . '</error>'
                ]);

            exit($e->getCode());
        }

        $this->output->writeln('Config test: <info>OK</info>');

        return $config;
    }
}