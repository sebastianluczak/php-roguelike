<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

class LoggerService
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function log(string $message)
    {
        $this->logger->info($message);
    }

    public function logMessage(\App\Message\CreatureGetsKilledMessage $message)
    {
        $this->writeLogHeader("LogMessage");
        $this->log("[" . get_class($message) . "]: Creature " . $message->getCreature()->getName() . " killed by player: " . $message->getPlayer()->getPlayerName());
    }

    public function logFight(\App\Model\Creature\CreatureInterface $creature, \App\Model\Player\PlayerInterface $player)
    {
        $this->writeLogHeader("FIGHT");
        $this->log("Creature: " . $creature->getName() . " fights player: " . $player->getPlayerName());
        $this->log("Creature Health: " . $creature->getHealth() . " | Player Health: " . $player->getHealth());
        $this->log("Creature Damage: " . $creature->getDamage() . " | Player Damage: " . $player->getDamageScore());
    }

    private function writeLogHeader(string $string)
    {
        $this->log("#=-- " . $string . " --=#");
    }
}