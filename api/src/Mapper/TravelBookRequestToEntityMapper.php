<?php

namespace App\Mapper;

use App\Entity\TravelBook;
use App\Payloads\Requests\CreateTravelBookRequest;
use App\Repository\TravelBookRepositoryInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;

#[AsMapper(from: CreateTravelBookRequest::class, to: TravelBook::class)]
readonly class TravelBookRequestToEntityMapper implements MapperInterface
{
    public function __construct(private TravelBookRepositoryInterface $travelBookRepository)
    {
    }

    public function load(object $from, string $toClass, array $context): object
    {
        $dto = $from;
        assert($dto instanceof CreateTravelBookRequest);
        $travelBook = null !== $dto->id ? $this->travelBookRepository->findOne(['id' => $dto->id]) : new TravelBook();

        if (!$travelBook) {
            throw new EntityNotFoundException('Travel book not found');
        }

        return $travelBook;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $dto = $from;
        assert($dto instanceof CreateTravelBookRequest);

        $entity = $to;
        assert($entity instanceof TravelBook);

        if (isset($dto->aboutTravel)) $entity->setDescription($dto->aboutTravel);
        if (isset($dto->title)) $entity->setTitle($dto->title);

        return $entity;
    }
}
