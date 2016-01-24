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

    public function recentTemperature(\DateInterval $dateInterval)
    {
        $date = new \DateTime();
        $date->sub($dateInterval);

        $results = $this->medoo->query(
            "select
                datetime,
                trim(both '°C\" ' from (boiler.data->'Set boiler water temperature')::text) as set_temperature,
                trim(both '°C\" ' from (boiler.data->'Boiler water temperature')::text) as actual_temperature
            from
                boiler
            WHERE
              datetime >= '".$date->format("c")."'
            ORDER BY
                datetime ASC");

        return $results->fetchAll(\PDO::FETCH_ASSOC);
    }
}