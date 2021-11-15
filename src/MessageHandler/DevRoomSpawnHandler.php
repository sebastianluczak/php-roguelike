<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Enum\Misc\AsciiEmoticonEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\DevRoomSpawnMessage;
use App\Message\RegenerateMapMessage;
use App\Service\GameService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class DevRoomSpawnHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected GameService $gameService;

    public function __construct(MessageBusInterface $messageBus, GameService $gameService)
    {
        $this->messageBus = $messageBus;
        $this->gameService = $gameService;
    }

    public function __invoke(DevRoomSpawnMessage $message)
    {
        $this->messageBus->dispatch(new AddAdventureLogMessage("[ADMIN] " . AsciiEmoticonEnum::FLIP_EM_ALL_TABLES() . " DevRoom generating.", MessageClassEnum::DEVELOPER()));
        $this->gameService->getMapService()->generateDevRoomMap();
    }
}