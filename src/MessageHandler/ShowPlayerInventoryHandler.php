<?php

namespace App\MessageHandler;

use App\Enum\Loot\LootTypeEnum;
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

    public function __invoke(ShowPlayerInventoryMessage $message): void
    {
        $player = $message->getPlayer();
        $inventory = $player->getInventory();

        $this->messageBus->dispatch(new AddAdventureLogMessage(
            'All items in bag: '.$inventory->getInventoryBag()->count().' - Health potions count: '.count($inventory->getInventoryBag()->getItemsOfType(LootTypeEnum::POTION()))
        ));
    }
}
