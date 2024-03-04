<?php

namespace App\Mapper;

use App\Entity\TravelBook;
use App\Payloads\Requests\CreateTravelBookRequest;
use App\Payloads\Responses\CreateTravelBookResponse;
use Symfonycasts\MicroMapper\AsMapper;
use Symfonycasts\MicroMapper\MapperInterface;

#[AsMapper(from: TravelBook::class, to: CreateTravelBookResponse::class)]
class TravelBookEntityToDtoMapper implements MapperInterface
{

    public function load(object $from, string $toClass, array $context): object
    {
        assert($from instanceof TravelBook);
        $travelBookResponse = new CreateTravelBookResponse();

        $travelBookResponse->id = $from->getId();

        return $travelBookResponse;
    }

    public function populate(object $from, object $to, array $context): object
    {
        $entity = $from;
        assert($entity instanceof TravelBook);
        $travelBookResponse = $to;
        assert($travelBookResponse instanceof CreateTravelBookResponse);

        $travelBookResponse->title = $entity->getTitle();
        $travelBookResponse->aboutTravel = $entity->getTitle();

        return $travelBookResponse;
    }
}
