<?php

namespace App\MessageHandler;

use App\Enum\GameIconEnum;
use App\Enum\Loot\LootTypeEnum;
use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\SellExcessItemsMessage;
use App\Model\Loot\LootInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class SellExcessItemsHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(SellExcessItemsMessage $message)
    {
        $player = $message->getPlayer();
        $totalPrice = 0;
        $weaponsToRemove = $player->getInventory()->getInventoryBag()->getItemsOfType(LootTypeEnum::WEAPON());
        /** @var LootInterface $weaponInInventory */
        foreach ($weaponsToRemove as $weaponInInventory) {
            $totalPrice += $weaponInInventory->getPriceValue();
            $player->getInventory()->getInventoryBag()->removeItem($weaponInInventory);
            $player->getInventory()->addGoldAmount($weaponInInventory->getPriceValue());
        }

        $armorsToRemove = $player->getInventory()->getInventoryBag()->getItemsOfType(LootTypeEnum::ARMOR());
        /* @var LootInterface $weaponInInventory */
        foreach ($armorsToRemove as $armorInInventory) {
            $totalPrice += $armorInInventory->getPriceValue();
            $player->getInventory()->getInventoryBag()->removeItem($armorInInventory);
            $player->getInventory()->addGoldAmount($armorInInventory->getPriceValue());
        }

        $this->messageBus->dispatch(
            new AddAdventureLogMessage(
                'Sold excess items and gained '.GameIconEnum::GOLD().' '.$totalPrice.' gold',
                MessageClassEnum::LOOT()
            )
        );
    }
}
