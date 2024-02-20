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

final readonly class ProductStateProvider implements ProviderInterface
{
    public function __construct(private ProductRepositoryInterface $productRepository, private Pagination $pagination)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if($operation instanceof CollectionOperationInterface){
            $currentPage = $this->pagination->getPage($context);
            $itemPerPage = $this->pagination->getLimit($operation, $context);
            $offset = $this->pagination->getOffset($operation, $context);
            $totalItems = $this->productRepository->countItems();
            $products = $this->productRepository->findProducts($itemPerPage, $currentPage);

            $productResources = [];
            foreach ($products as $product) {
                $productResource = new ProductResource();
                $productResource->id = $product->getId();
                $productResource->name = $product->getName();
                $productResource->desc = $product->getDescription();
                $productResource->unitPrice = $product->getPrice();
                $productResources[] = $productResource;
            }

            return new TraversablePaginator(
                new \ArrayIterator($productResources),
                $currentPage,
                $itemPerPage,
                $totalItems
            );
        }
        $product = $this->productRepository->findOne($uriVariables);
        $productResource = new ProductResource();
        $productResource->name = $product->getName();
        $productResource->desc = $product->getDescription();
        $productResource->unitPrice = $product->getPrice();
        $productResource->id = $product->getId();

        return $productResource;
    }
}
