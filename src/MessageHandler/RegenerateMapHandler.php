<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\RegenerateMapMessage;
use App\Service\GameService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RegenerateMapHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected GameService $gameService;

    public function __construct(MessageBusInterface $messageBus, GameService $gameService)
    {
        $this->messageBus = $messageBus;
        $this->gameService = $gameService;
    }

    public function __invoke(RegenerateMapMessage $message)
    {
        $this->messageBus->dispatch(new AddAdventureLogMessage('[ADMIN] Map regenerate initiated', MessageClassEnum::DEVELOPER()));
        $this->gameService->getMapService()->resetErrors();
        $this->gameService->getMapService()->createNewLevel();
    }
}
