<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use App\Entity\TravelBook;
use App\Payloads\Requests\CreateTravelBookRequest;
use App\State\Processor\EntityToResourceStateProcessor;
use App\State\Processor\PersistAsyncStateProcessor;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: "Travel book",
    operations: [
            new Post(status: 202, validationContext: ['groups' => ['postValidation']], input: CreateTravelBookRequest::class, output: false, messenger: true, processor: PersistAsyncStateProcessor::class)
    ],
    routePrefix: "/api",
    security: "is_granted('ROLE_USER')",
)]
class TravelBookResource
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    #[Assert\NotBlank(message: "Blank value prohibited", groups: ['postValidation'])]
    #[Assert\NotNull(message: "Null value prohibited", groups: ['postValidation'])]
    #[Assert\Length(min: 50, max: 200, minMessage: "Too short, should be more than 50 characters", maxMessage: "Too long, should be less then 200 characters")]
    public string $titleTravel;

    public ?string $about;
}
