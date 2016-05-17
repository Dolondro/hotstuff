<?php

namespace Dolondro\HotStuff\Process;

use Symfony\Component\Process\Exception\ProcessFailedException;
use Symfony\Component\Process\Process;

class ScraperProcess
{
    public function run($username, $password)
    {
        $script = realpath(__DIR__."/../casper/scraper.js");
        $process = new Process("casperjs {$script} --user='{$username}' --password='{$password}'  --ignore-ssl-errors=true --ssl-protocol=any");
        $process->run();

        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        $response = $process->getOutput();

        $f = fopen("/tmp/response.txt", "w");
        fwrite($f, $response);
        fclose($f);
    }
}