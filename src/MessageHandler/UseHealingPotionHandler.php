<?php

namespace App\MessageHandler;

use App\Enum\GameIconEnum;
use App\Enum\Loot\LootTypeEnum;
use App\Enum\MessageClassEnum;
use App\Enum\Player\Health\HealthActionEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\UseHealingPotionMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class UseHealingPotionHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(UseHealingPotionMessage $message): void
    {
        $player = $message->getPlayer();
        $healingPotionInInventoryFilter = $player->getInventory()->getInventoryBag()->getItemsOfType(LootTypeEnum::POTION());
        if (count($healingPotionInInventoryFilter) > 0) {
            $healingPotions = $player->getInventory()->getInventoryBag()->getItemsOfType(LootTypeEnum::POTION());
            if (count($healingPotions) > 0) {
                $healingPotion = end($healingPotions);
                $player->getHealth()->modifyHealth($player->getHealth()->getMaxHealth(), HealthActionEnum::INCREASE());
                $player->getInventory()->getInventoryBag()->removeItem($healingPotion);

                $this->messageBus->dispatch(
                    new AddAdventureLogMessage(
                    'Healed using '.GameIconEnum::POTION().' '.$healingPotions[0]->getName(),
                    MessageClassEnum::LOOT()
                )
                );
            }
        } else {
            $this->messageBus->dispatch(
                new AddAdventureLogMessage(
                'No more healing potions left',
                MessageClassEnum::IMPORTANT()
            )
            );
        }
    }
}
