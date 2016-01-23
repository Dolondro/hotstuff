<?php

namespace Dolondro\HotStuff\Storage;

class PostgresStorage implements StorageInterface
{
    protected $medoo;

    public function __construct(\medoo $medoo)
    {
        $this->medoo = $medoo;
    }

    public function insert(\DateTime $dateTime, $state, $data=[], $error="")
    {
        $response = $this->medoo->insert("boiler", [
            "datetime" => $dateTime->format("c"),
            "state" => $state,
            "data" => json_encode($data),
            "error" => $error
        ]);
    }

    public function between($startdate, $enddate)
    {
        return $this->medoo->select("boiler", "*", [
            "datetime[<>]" => [$startdate, $enddate]
        ]);
    }
}