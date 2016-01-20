<?php

namespace Dolondro\Boiler\Command;

use Dolondro\Boiler\Process\ScraperProcess;
use Dolondro\Boiler\Storage\StorageInterface;
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
        $this->logger->addAlert("SOMETHING HAS GONE WRONG");
        die();
        //$process = new Process("casperjs scraper.js --user={$this->boilerUsername} --password={$this->boilerPassword}", __DIR__."/../casper");
        $process = new Process("ls");
        $process->setTimeout(120);
        $process->run();

        $data = $process->getOutput();
        $exploded = explode("=== RESULTS ===", $data);
        $results = json_decode($exploded[1], true);
        $success = !!$process->isSuccessful();

        if ($success) {
            $this->storageInterface->insert(new \DateTime(), true, $results["data"]);
        } else {
            $this->storageInterface->insert(new \DateTime(), false, [], $results["error"]);
        }
    }
}
