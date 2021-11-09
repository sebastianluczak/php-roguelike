<?php

namespace App\Message;

use App\Model\Creature\CreatureInterface;
use App\Model\Player\PlayerInterface;

/**
 * Used at:
 * CreatureGetsKilledHandler::__invoke()
 */
class CreatureGetsKilledMessage
{
    protected CreatureInterface $creature;
    protected PlayerInterface $player;

    public function __construct(CreatureInterface $creature, PlayerInterface $player)
    {
        $this->creature = $creature;
        $this->player = $player;
    }

    /**
     * @return CreatureInterface
     */
    public function getCreature(): CreatureInterface
    {
        return $this->creature;
    }

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }
}