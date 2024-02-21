<?php

namespace App\State\Processor;

use ApiPlatform\Doctrine\Common\State\PersistProcessor;
use ApiPlatform\Doctrine\Common\State\RemoveProcessor;
use ApiPlatform\Metadata\DeleteOperationInterface;
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
use function PHPUnit\Framework\assertArrayHasKey;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\isInstanceOf;

readonly class ProductDeleteProcessor implements ProcessorInterface
{
    public function __construct(#[Autowire(service: RemoveProcessor::class)] private ProcessorInterface $deleteProcessor, private ProductRepositoryInterface $productRepository)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        assertArrayHasKey('id', $uriVariables);
        $product = $this->productRepository->findOne(['id' => $uriVariables['id']]);
        $this->deleteProcessor->process($product, $operation, $uriVariables, $context);
    }
}
