<?php

namespace App\Model\RandomEvent\Effect;

use App\Model\Player\PlayerInterface;

class RegenHealthEffect implements GameEffectInterface
{
    protected PlayerInterface $player;

    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
    }

    public function apply(): void
    {
        $this->player->increaseHealth(1);
    }
}