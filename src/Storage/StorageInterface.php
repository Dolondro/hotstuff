<?php

namespace Dolondro\Boiler\Storage;

interface StorageInterface
{
    // date, state, data
    public function insert(\DateTime $dateTime, $state, $data=[], $error="");
}