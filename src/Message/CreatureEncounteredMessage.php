<?php

namespace App\Message;

use App\Model\Creature\CreatureInterface;
use App\Model\Player\PlayerInterface;

/**
 * Used at:
 * CreatureEncounteredHandler::__invoke().
 */
class CreatureEncounteredMessage
{
    protected CreatureInterface $creature;
    protected PlayerInterface $player;

    public function __construct(CreatureInterface $creature, PlayerInterface $player)
    {
        $this->creature = $creature;
        $this->player = $player;
    }

    public function getCreature(): CreatureInterface
    {
        return $this->creature;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }
}
