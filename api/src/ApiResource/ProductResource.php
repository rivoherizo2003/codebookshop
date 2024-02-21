<?php

namespace App\ApiResource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Entity\Product;
use App\State\Processor\ProductDeleteProcessor;
use App\State\Processor\ProductProcessor;
use App\State\Provider\ProductProvider;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource(
    shortName: "Product",
    operations: [
        new GetCollection(provider: ProductProvider::class,
            stateOptions: new Options(entityClass: Product::class)),
        new Get(provider: ProductProvider::class,
            stateOptions: new Options(entityClass: Product::class)),
        new Post(validationContext: ['groups' => ['postValidation']], processor: ProductProcessor::class),
        new Patch(requirements: ['id' => '\d+'], processor: ProductProcessor::class, stateOptions: new Options(entityClass: Product::class)),
        new Put(validationContext: ['groups' => ['putValidation']], processor: ProductProcessor::class),
        new Delete(processor: ProductDeleteProcessor::class, stateOptions: new Options(entityClass: Product::class))
    ],
    routePrefix: "/api",
    security: "is_granted('ROLE_USER')",

)]
class ProductResource
{
    #[ApiProperty(readable: false, writable: false, identifier: true)]
    public ?int $id = null;

    #[Assert\NotBlank(message: "Blank value prohibited", groups: ['putValidation', 'postValidation'])]
    #[Assert\NotNull(message: "Null value prohibited", groups: ['putValidation', 'postValidation'])]
    #[Assert\Length(min: 10, max: 200, minMessage: "Too short, should be more than 10 characters", maxMessage: "Too long, should be less then 200 characters")]
    public string $name;

    public ?string $desc = null;

    #[Assert\GreaterThanOrEqual(value: 0, message: "Should be zero or more than zero")]
    public ?float $unitPrice = 0;
}
