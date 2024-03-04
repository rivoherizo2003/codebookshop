<?php

namespace App\Handler;

use App\Entity\TravelBook;
use App\Mapper\TravelBookRequestToEntityMapper;
use App\Payloads\Requests\CreateTravelBookRequest;
use App\Repository\TravelBookRepositoryInterface;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfonycasts\MicroMapper\MicroMapperInterface;

#[AsMessageHandler]
class CreateTravelBookHandler
{
    public function __construct(
        private MicroMapperInterface $mapper,
        private TravelBookRepositoryInterface $travelBookRepository
    )
    {
    }

    public function __invoke(CreateTravelBookRequest $createTravelBookRequest)
    {
        $travelBook = $this->mapper->map($createTravelBookRequest, TravelBook::class);
        $this->travelBookRepository->add($travelBook);
    }
}
