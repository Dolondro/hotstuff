<?php

namespace Dolondro\HotStuff\Command;

use Dolondro\HotStuff\Process\ScraperProcess;
use Dolondro\HotStuff\Storage\StorageInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class ImportLegacyJsonCommand extends Command
{
    protected $storageInterface;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(StorageInterface $storageInterface)
    {
        parent::__construct();
        $this->storageInterface = $storageInterface;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('boiler:import:legacy')
             ->setDescription("Import legacy json data format into postgres format")
             ->addArgument("filename",InputArgument::REQUIRED, "Filename");
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $f = fopen($input->getArgument("filename"), "r");

        while(($line = fgets($f))!==false) {
            $this->parseAndImport($line);
        }
    }

    protected function parseAndImport($line)
    {
        $array = json_decode($line, true);
        $datetime = $array["datetime"];
        unset($array["state"]);
        unset($array["datetime"]);

        if (isset($array["data"])) {
            $array = $array["data"];
        }

        $this->storageInterface->insert(new \DateTime($datetime), true, $array);
    }
}
