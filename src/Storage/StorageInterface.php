<?php

namespace Dolondro\HotStuff\Storage;

interface StorageInterface
{
    // date, state, data
    public function insert(\DateTime $dateTime, $state, $data=[], $error="");

    public function between($startdate, $enddate);

    public function recentTemperature(\DateInterval $dateInterval);
}