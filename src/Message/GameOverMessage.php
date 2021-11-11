<?php

namespace App\Message;

use App\Model\Creature\CreatureInterface;
use App\Model\Player\PlayerInterface;

/**
 * Used at:
 * GameOverHandler::__invoke()
 */
class GameOverMessage
{
    protected PlayerInterface $player;

    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
    }

    /**
     * @return PlayerInterface
     */
    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }
}