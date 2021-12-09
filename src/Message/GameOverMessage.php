<?php

namespace App\Message;

use App\Model\Player\PlayerInterface;

/**
 * Used at:
 * GameOverHandler::__invoke().
 */
class GameOverMessage
{
    protected PlayerInterface $player;
    protected string $reason;

    public function __construct(PlayerInterface $player, string $reason)
    {
        $this->player = $player;
        $this->reason = $reason;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function getReason(): string
    {
        return $this->reason;
    }
}
