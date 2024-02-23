<?php

namespace App\State\Provider;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination\TraversablePaginator;
use ApiPlatform\State\ProviderInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfonycasts\MicroMapper\MicroMapperInterface;

final readonly class EntityToResourceStateProvider implements ProviderInterface
{
    public function __construct(#[Autowire(service: CollectionProvider::class)] private ProviderInterface $collectionProvider, #[Autowire(service: ItemProvider::class)] private ProviderInterface $itemProvider, private MicroMapperInterface $mapper)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $resourceClass = $operation->getClass();
        if ($operation instanceof CollectionOperationInterface) {
            $entities = $this->collectionProvider->provide($operation, $uriVariables, $context);
            assert($entities instanceof Paginator);
            $resources = [];
            foreach ($entities as $entity) {
                $resources[] = $this->mapEntityToApiResource($entity, $resourceClass);
            }

            return new TraversablePaginator(
                new \ArrayIterator($resources),
                $entities->getCurrentPage(),
                $entities->getItemsPerPage(),
                $entities->getTotalItems()
            );
        }

        $entity = $this->itemProvider->provide($operation, $uriVariables, $context);

        if (!$entity) {
            return null;
        }

        return $this->mapEntityToApiResource($entity, $resourceClass);
    }

    private function mapEntityToApiResource(object $entity, string $resourceClass): object
    {
        return $this->mapper->map($entity, $resourceClass);
    }
}
