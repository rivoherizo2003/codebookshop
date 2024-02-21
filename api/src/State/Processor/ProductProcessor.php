<?php

namespace App\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\ProductResource;
use App\Entity\Product;
use App\Repository\ProductRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\isInstanceOf;

readonly class ProductProcessor implements ProcessorInterface
{
    public function __construct(#[Autowire(service: PersistProcessor::class)] private ProcessorInterface $persistProcessor, private ProductRepositoryInterface $productRepository)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        assert($data instanceof ProductResource);
        $data->id = $uriVariables['id'] ?? null;
        $product = $this->mapToEntity($data);
        $this->persistProcessor->process($product, $operation, $uriVariables, $context);

        return $data;
    }

    private function mapToEntity(ProductResource $productResource): ?Product
    {

        if(null !== $productResource->id){
            $product = $this->productRepository->findOne(['id' => $productResource->id]);

            if(!$product){
                throw new NotFoundHttpException(sprintf('Product with id %d not found', $productResource->id));
            }
        } else {
            $product = new Product();
        }
        if(isset($productResource->name)) $product->setName($productResource->name);
        if($productResource->desc) $product->setDescription($productResource->desc);
        if($productResource->unitPrice) $product->setPrice($productResource->unitPrice);

        return $product;
    }
}
