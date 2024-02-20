<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;

#[ApiResource(
    operations: [
        new Get()
    ]
)]
class DataWeather
{
    private \stdClass $coord;

    private array $weather;

    public function getWeather(): array
    {
        return $this->weather;
    }

    public function setWeather(array $weather): void
    {
        $this->weather = $weather;
    }

    public function getCoord(): \stdClass
    {
        return $this->coord;
    }

    public function setCoord(\stdClass $coord): void
    {
        $this->coord = $coord;
    }


}
