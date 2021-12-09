<?php

namespace App\Message;

use App\Model\Player\PlayerInterface;

class PlayerLevelUpMessage
{
    protected PlayerInterface $player;

    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
    }

    public function getPlayer(): PlayerInterface
    {
        return $this->player;
    }
}
