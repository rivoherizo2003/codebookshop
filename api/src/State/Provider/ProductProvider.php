<?php

namespace App\State\Provider;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\Pagination;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use App\ApiResource\ProductResource;
use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Repository\ProductRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;

final readonly class ProductProvider implements ProviderInterface
{
//    public function __construct(private ProductRepositoryInterface $productRepository, private Pagination $pagination)
//    {
//    }

public function __construct(#[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider, #[Autowire(service: ItemProvider::class)] private ProviderInterface $itemProvider)
{
}

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if($operation instanceof CollectionOperationInterface){
            return $this->collectionProvider->provide($operation, $uriVariables, $context);
        }

        return $this->itemProvider->provide($operation, $uriVariables, $context);
    }
}
