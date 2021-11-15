<?php

namespace App\MessageHandler;

use App\Enum\GameIconEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\ShowPlayerInventoryMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class ShowPlayerInventoryHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(ShowPlayerInventoryMessage $message)
    {
        $player = $message->getPlayer();
        $inventory = $player->getInventory();

        // TODO add this - print inventory of player
    }
}