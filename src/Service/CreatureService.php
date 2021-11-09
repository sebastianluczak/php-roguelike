<?php

namespace App\Service;

use App\Exception\GameOverException;
use App\Message\AddAdventureLogMessage;
use App\Message\CreatureGetsKilledMessage;
use App\Model\Creature\CreatureInterface;
use App\Model\Player\PlayerInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class CreatureService
{
    protected MessageBusInterface $messageBus;
    protected LoggerService $loggerService;

    public function __construct(MessageBusInterface $messageBus, LoggerService $loggerService)
    {
        $this->messageBus = $messageBus;
        $this->loggerService = $loggerService;
    }

    /**
     * @throws GameOverException
     */
    public function handleFight(CreatureInterface $creature, PlayerInterface $player): void
    {
        $this->loggerService->logFight($creature, $player);
        $this->messageBus->dispatch(new AddAdventureLogMessage("Creature: " . $creature->getName() . " fights player: " . $player->getPlayerName()));

        while ($creature->getHealth() >= 0) {
            if ($player->getHealth() <= 0) {
                throw new GameOverException($creature);
            }
            // calculate creature hit damage
            $playerDamageReduction = $player->getArmorScore() / 100;
            $creatureHitDamage = round($creature->getDamage() - ($playerDamageReduction * $creature->getDamage()), 0);
            // calculate player damage
            $creatureDamageReduction = $creature->getArmor() / 100;
            $playerHitDamage = $player->getDamageScore() - ($creatureDamageReduction * $player->getDamageScore());
            // take damage first
            $player->decreaseHealth($creatureHitDamage);
            $creature->decreaseHealth($playerHitDamage);
        }

        if ($creature->getHealth() <= 0) {
            // messageBus
            $this->messageBus->dispatch(new CreatureGetsKilledMessage($creature, $player));
        }
    }
}