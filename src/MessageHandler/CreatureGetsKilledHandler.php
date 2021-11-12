<?php

namespace App\MessageHandler;

use App\Enum\MessageClassEnum;
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
        $this->messageBus->dispatch(new AddAdventureLogMessage("You've killed " . $creature->getName(), MessageClassEnum::SUCCESS()));

        $gold = $creature->handleLoot();
        $player->addGoldAmount($gold->getAmount());
        $this->messageBus->dispatch(new AddAdventureLogMessage("You've earned 💰 " . $gold->getAmount() . " gold", MessageClassEnum::SUCCESS()));
        $initialPlayerLevel = $player->getLevel();
        $player->increaseExperience($creature->getExperience());
        $currentPlayerLevel = $player->getLevel();
        $this->messageBus->dispatch(new AddAdventureLogMessage("You've earned 🧍 " . $creature->getExperience() . " experience points", MessageClassEnum::SUCCESS()));
        $player->increaseKillCount();

        // level up handler
        if ($currentPlayerLevel > $initialPlayerLevel) {
            $this->messageBus->dispatch(new PlayerLevelUpMessage($player));
        }
        if ($player->getHealth() <= 40) {
            $this->messageBus->dispatch(new AddAdventureLogMessage("Your health is low, find a way to heal.", MessageClassEnum::IMPORTANT()));
        }
    }
}