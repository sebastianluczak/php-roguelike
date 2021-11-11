<?php

namespace App\MessageHandler;

use App\Message\AddAdventureLogMessage;
use App\Message\GameEffectMessage;
use App\Message\TileLogicMessage;
use App\Service\GameService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TileLogicHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected GameService $gameService;

    public function __construct(MessageBusInterface $messageBus, GameService $gameService)
    {
        $this->messageBus = $messageBus;
        $this->gameService = $gameService;
    }

    public function __invoke(TileLogicMessage $message)
    {
        $player = $message->getPlayer();
        $tileLogic = $message->getTileLogic();
        $tileLogic->process($player);

        if ($tileLogic->hasAdventureLogMessage()) {
            $this->messageBus->dispatch(new AddAdventureLogMessage($tileLogic->getAdventureLogMessage(), $tileLogic->getAdventureLogMessageClass()));
        }

        if ($tileLogic->hasEvent()) {
            $event = $tileLogic->getEvent();
            $this->messageBus->dispatch(new GameEffectMessage($event));
        }
    }
}