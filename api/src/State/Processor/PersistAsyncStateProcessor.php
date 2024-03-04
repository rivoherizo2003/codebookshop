<?php

namespace App\State\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PersistAsyncStateProcessor implements ProcessorInterface
{
    public function __construct(private MessageBusInterface $messageBus)
    {
    }

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $this->messageBus->dispatch($data);
    }
}
