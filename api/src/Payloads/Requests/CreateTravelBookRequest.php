<?php

namespace App\Payloads\Requests;

use ApiPlatform\Metadata\ApiProperty;

final class CreateTravelBookRequest
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    public string $title;

    public string $aboutTravel;
}
