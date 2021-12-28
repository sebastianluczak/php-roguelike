<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\CityPortalMessage;
use App\Message\SaveGameMessage;
use App\Model\CityMap;
use App\Service\GameService;
use App\Service\LoggerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SaveGameHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected LoggerService $loggerService;

    public function __construct(MessageBusInterface $messageBus, LoggerService $loggerService)
    {
        $this->messageBus = $messageBus;
        $this->loggerService = $loggerService;
    }

    public function __invoke(SaveGameMessage $message): void
    {
        // save it to database todo
        foreach ($message->getStateOfGame()->toArray() as $serializerClass => $data) {
            $this->loggerService->log("Class: " . $serializerClass . " for deserialization with data: " . $data);
        }
    }
}
