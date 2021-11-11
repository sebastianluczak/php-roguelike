<?php

namespace App\Service;

use App\Enum\MessageClassEnum;
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
        $this->messageBus->dispatch(new AddAdventureLogMessage(
            "☠️ Encountered " . $creature->getName() . " 💗" . $creature->getHealth() . "/🗡️" . $creature->getDamage() . "/🛡️" . $creature->getArmor(),
            MessageClassEnum::IMPORTANT()
        ));

        $turn = 1;
        while ($creature->getHealth() >= 0) {
            if ($player->getHealth() <= 0) {
                throw new GameOverException($creature);
            }
            // calculate creature hit damage
            // todo check those values
            $playerDamageReduction = $player->getArmorScore() / 100;
            $creatureHitDamage = round($creature->getDamage() - ($playerDamageReduction * $creature->getDamage()), 0);
            // calculate player damage
            // todo check those values
            $creatureDamageReduction = $creature->getArmor() / 100;
            $playerHitDamage = round($player->getDamageScore() - ($creatureDamageReduction * $player->getDamageScore()), 0);

            // todo add items bypassing % armor
            // take damage first
            // todo initiative rolls
            $this->messageBus->dispatch(new AddAdventureLogMessage("🗡️ Turn " . $turn . " - " . $creature->getName() . " (💗" . $creature->getHealth() . "/🗡".$creatureHitDamage.") vs. " . $player->getPlayerName() . " (💗" . $player->getHealth() . "/🗡".$playerHitDamage.")", MessageClassEnum::STANDARD()));
            $player->decreaseHealth($creatureHitDamage);
            $creature->decreaseHealth($playerHitDamage);
            $turn++;
        }

        if ($creature->getHealth() <= 0) {
            // messageBus
            $this->messageBus->dispatch(new CreatureGetsKilledMessage($creature, $player));
        }
    }
}