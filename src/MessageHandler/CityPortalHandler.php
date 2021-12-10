<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\CityPortalMessage;
use App\Model\CityMap;
use App\Service\GameService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CityPortalHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected GameService $gameService;

    public function __construct(MessageBusInterface $messageBus, GameService $gameService)
    {
        $this->messageBus = $messageBus;
        $this->gameService = $gameService;
    }

    public function __invoke(CityPortalMessage $message): void
    {
        if ($this->gameService->getMapService()->getMap() instanceof CityMap) {
            // player is in the city, teleport him to normal map
            $this->messageBus->dispatch(new AddAdventureLogMessage('Portal to '.$this->gameService->getMapService()->getMap()->getName().' initiated.', MessageClassEnum::STANDARD()));

            $this->gameService->getMapService()->setMap($this->gameService->getMapService()->getTempDungeonMap());
        } else {
            // player in dungeon, wants to go into the city
            $this->messageBus->dispatch(new AddAdventureLogMessage('Portal to '.$this->gameService->getMapService()->getCityMap()->getName().' initiated.', MessageClassEnum::STANDARD()));

            $this->gameService->getMapService()->setTempDungeonMap($this->gameService->getMapService()->getMap());
            $this->gameService->getMapService()->setMap($this->gameService->getMapService()->getCityMap());
        }
    }
}
