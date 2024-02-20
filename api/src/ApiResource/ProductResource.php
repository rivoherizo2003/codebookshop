<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\State\Provider\ProductStateProvider;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: "Product",
    operations: [
        new GetCollection(),
        new Get()
    ], routePrefix: "api",
    security: "is_granted('ROLE_USER')",provider: ProductStateProvider::class
)]
class ProductResource
{
    public ?int $id = null;

    #[Assert\NotBlank(message: "Blank value prohibited")]
    #[Assert\NotNull(message: "Null value prohibited")]
    #[Assert\Length(min: 10, max: 200, minMessage: "Too short, should be more than 10 characters", maxMessage: "Too long, should be less then 200 characters")]
    public string $name;

    public ?string $desc = null;

    #[Assert\GreaterThanOrEqual(value: 0, message: "Should be zero or more than zero")]
    public ?float $unitPrice = 0;
}
