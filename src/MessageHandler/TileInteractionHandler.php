<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\TileInteractionMessage;
use App\Service\GameService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class TileInteractionHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;
    protected GameService $gameService;

    public function __construct(MessageBusInterface $messageBus, GameService $gameService)
    {
        $this->messageBus = $messageBus;
        $this->gameService = $gameService;
    }

    public function __invoke(TileInteractionMessage $message)
    {
        $player = $message->getPlayer();
        $tileInteraction = $message->getTileInteraction();

        $player->setInDialogue(true);
        $player->setCurrentDialogue($tileInteraction->getDialogue());

        $this->messageBus->dispatch(new AddAdventureLogMessage("[DEBUG] ", MessageClassEnum::LOOT()));
    }
}
