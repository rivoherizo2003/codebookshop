<?php

namespace App\Mapper;

use App\ApiResource\ProductResource;
use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use function PHPUnit\Framework\assertInstanceOf;
#[AsMapper(from: Product::class, to: ProductResource::class)]
class ProductEntityToResourceMapper implements MapperInterface
{
    public function load(object $from, string $toClass, array $context): object
    {
        assert($from instanceof Product);
        $productResource = new ProductResource();

        $productResource->id = $from->getId();
        $productResource->name = $from->getName();
        $productResource->desc = $from->getDescription();
        $productResource->unitPrice = $from->getPrice();

        return $productResource;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        assert($entity instanceof Product);
        $productResource = $to;
        assert($productResource instanceof ProductResource);

        $productResource->name = $entity->getName();
        $productResource->desc = $entity->getDescription();
        $productResource->unitPrice = $entity->getPrice();

        return $productResource;
    }
}
