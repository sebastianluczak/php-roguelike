<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Enum\Player\Health\HealthActionEnum;
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

    public function __invoke(PlayerLevelUpMessage $message): void
    {
        $player = $message->getPlayer();
        $initialPlayerLevel = $message->getInitialPlayerLevel();
        if ($player->getLevel()->getLevel() > $initialPlayerLevel) {
            $levelsToGain = $player->getLevel()->getLevel() - $initialPlayerLevel;
            for ($x = 0; $x < $levelsToGain; ++$x) {
                $statChosen = $this->specialStats[array_rand($this->specialStats)];
                $randomSkillBoostMethod = 'modify'.$statChosen;
                $player->getStats()->{$randomSkillBoostMethod}(sqrt($player->getStats()->getIntelligence()));

                $player->getHealth()->increaseMaxHealth(10);
                $player->getHealth()->modifyHealth($player->getHealth()->getMaxHealth(), HealthActionEnum::INCREASE());
                $this->messageBus->dispatch(new AddAdventureLogMessage('Player skill leveled up: '.$statChosen, MessageClassEnum::LOOT()));
            }
        }
    }
}
