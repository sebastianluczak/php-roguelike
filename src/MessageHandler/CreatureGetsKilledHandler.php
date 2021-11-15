<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
use App\Enum\Player\Level\LevelActionEnum;
use App\Message\AddAdventureLogMessage;
use App\Message\CreatureGetsKilledMessage;
use App\Message\PlayerLevelUpMessage;
use App\Service\LoggerService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreatureGetsKilledHandler implements MessageHandlerInterface
{
    protected LoggerService $loggerService;
    protected MessageBusInterface $messageBus;

    public function __construct(LoggerService $loggerService, MessageBusInterface $messageBus)
    {
        $this->loggerService = $loggerService;
        $this->messageBus = $messageBus;
    }

    public function __invoke(CreatureGetsKilledMessage $message)
    {
        $player = $message->getPlayer();
        $creature = $message->getCreature();

        $this->loggerService->logMessage($message);

        $gold = $creature->handleLoot();
        $this->messageBus->dispatch(new AddAdventureLogMessage("You've killed " . $creature->getName() . " earning 💰 " . $gold->getAmount() . " gold and 🧍 " . $creature->getExperience() . " experience points", MessageClassEnum::SUCCESS()));

        $player->addGoldAmount($gold->getAmount());
        // increase level handler
        $initialPlayerLevel = $player->getLevel()->getLevel();
        $player->getLevel()->modifyExperience($creature->getExperience(), LevelActionEnum::INCREASE());
        $currentPlayerLevel = $player->getLevel()->getLevel();
        $player->increaseKillCount();

        // level up handler
        if ($currentPlayerLevel > $initialPlayerLevel) {
            $this->messageBus->dispatch(new PlayerLevelUpMessage($player));
        }

        if ($player->getHealth()->getHealth() <= $player->getHealth()->getWarningThreshold()) {
            $this->messageBus->dispatch(new AddAdventureLogMessage("Your health is low, find a way to heal.", MessageClassEnum::IMPORTANT()));
        }
    }
}