<?php

namespace App\Mapper;

use App\ApiResource\ProductResource;
use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;
use function PHPUnit\Framework\assertInstanceOf;
#[AsMapper(from: ProductResource::class, to: Product::class)]
class ProductResourceToEntityMapper implements MapperInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository)
    {
    }

    /**
     * @throws EntityNotFoundException
     */
    public function load(object $from, string $toClass, array $context): object
    {
        $resource = $from;
        assert($resource instanceof ProductResource);

        $product = $resource->id ? $this->productRepository->findOne(['id' => $resource->id]): new Product();
        if(!$product){
            throw new EntityNotFoundException('Product not found');
        }

        return $product;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $resource = $from;
        assert($resource instanceof ProductResource);
        $entity = $to;
        assert($entity instanceof Product);

        if(isset($resource->name)) $entity->setName($resource->name);
        if(isset($resource->desc)) $entity->setDescription($resource->desc);
        if(isset($resource->unitPrice)) $entity->setPrice($resource->unitPrice);

        return $entity;
    }
}
