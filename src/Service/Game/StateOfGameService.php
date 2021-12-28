<?php

declare(strict_types=1);

namespace App\Service\Game;

use App\Factory\Game\StateOfGameFactory;
use App\Message\SaveGameMessage;
use App\Model\Game\StateOfGameModel;
use App\Model\Player\PlayerInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Serializer\SerializerInterface;

class StateOfGameService
{
    protected SerializerInterface $serializer;
    protected MessageBusInterface $messageBus;
    protected StateOfGameFactory $stateOfGameFactory;
    protected StateOfGameModel $stateOfGameModel;

    public function __construct(SerializerInterface $serializer, StateOfGameFactory $stateOfGameFactory, MessageBusInterface $messageBus)
    {
        $this->serializer = $serializer;
        $this->stateOfGameFactory = $stateOfGameFactory;
        $this->messageBus = $messageBus;
        $this->stateOfGameModel = $this->stateOfGameFactory->create();
    }

    protected function getGameState(): ArrayCollection
    {
        $gameState = new ArrayCollection();

        $playerCoordinates = $this->serializer->serialize($this->stateOfGameModel->getPlayerCoordinates(), 'json');
        $gameState->set(get_class($this->stateOfGameModel->getPlayerCoordinates()), $playerCoordinates);

        $playerStats = $this->serializer->serialize($this->stateOfGameModel->getPlayerStats(), 'json');
        $gameState->set(get_class($this->stateOfGameModel->getPlayerStats()), $playerStats);

        // todo add more fields to save

        return $gameState;
    }

    public function saveGameStateForPlayer(PlayerInterface $player)
    {
        $this->stateOfGameModel->savePlayerCoordinates($player->getCoordinates());
        $this->stateOfGameModel->savePlayerStates($player->getStats());
        $currentGameState = $this->getGameState();
        $this->saveGameStateAsync($currentGameState);
    }

    private function saveGameStateAsync(ArrayCollection $currentGameState)
    {
        $this->messageBus->dispatch(new SaveGameMessage($currentGameState));
        // todo logic of sending async message to symfony messenger and past to redis
    }
}
