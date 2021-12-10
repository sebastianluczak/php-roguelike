<?php

namespace App\Message;

use App\Model\Player\PlayerInterface;

class PlayerLevelUpMessage
{
    protected PlayerInterface $player;
    protected int $initialPlayerLevel;

    public function __construct(PlayerInterface $player, int $initialPlayerLevel)
    {
        $this->player = $player;
        $this->initialPlayerLevel = $initialPlayerLevel;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }

    public function getInitialPlayerLevel(): int
    {
        return $this->initialPlayerLevel;
    }
}
