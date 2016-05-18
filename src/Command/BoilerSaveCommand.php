<?php

namespace Dolondro\HotStuff\Command;

use Dolondro\HotStuff\Process\ScraperProcess;
use Dolondro\HotStuff\Storage\StorageInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Process\Process;

class BoilerSaveCommand extends Command
{
    protected $storageInterface;
    protected $boilerUsername;
    protected $boilerPassword;

    /**
     * @var Logger
     */
    protected $logger;

    public function __construct(StorageInterface $storageInterface, $boilerUsername, $boilerPassword)
    {
        parent::__construct();
        $this->storageInterface = $storageInterface;
        $this->boilerUsername = $boilerUsername;
        $this->boilerPassword = $boilerPassword;
    }

    public function setLogger(Logger $logger)
    {
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this->setName('boiler:save')
             ->setDescription('Boiler save');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $process = new Process("casperjs scraper.js --user={$this->boilerUsername} --password={$this->boilerPassword} --ignore-ssl-errors=true --ssl-protocol=any", __DIR__."/../Casper");
        $process->setTimeout(120);
        $process->run();

        $data = $process->getOutput();
        $exploded = explode("=== RESULTS ===", $data);
        if (count($exploded) < 2) {

        } else {
            $results = json_decode($exploded[1], true);
            if ($process->isSuccessful()) {
                $this->storageInterface->insert(new \DateTime(), true, $results["data"]);
            } else {
                $this->storageInterface->insert(new \DateTime(), false, [], $results["error"]);
            }
        }

    }
}
