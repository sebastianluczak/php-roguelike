<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\PlayerLevelUpMessage;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class PlayerLevelUpHandler implements MessageHandlerInterface
{
    protected MessageBusInterface $messageBus;

    protected array $specialStats = ['Strength', 'Perception', 'Endurance', 'Charisma', 'Intelligence', 'Agility', 'Luck'];

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function __invoke(PlayerLevelUpMessage $message)
    {
        $player = $message->getPlayer();
        $statChosen = $this->specialStats[array_rand($this->specialStats)];
        $randomSkillBoostMethod = 'modify' . $statChosen;
        $player->getStats()->{$randomSkillBoostMethod}(1);
        $player->getHealth()->increaseHealth($player->getHealth()->getMaxHealth());
        $this->messageBus->dispatch(new AddAdventureLogMessage("Player skill leveled up: " . $statChosen, MessageClassEnum::LOOT()));
    }
}