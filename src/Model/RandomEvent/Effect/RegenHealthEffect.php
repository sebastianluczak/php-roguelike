<?php

namespace App\Model\RandomEvent\Effect;

use App\Model\Player\PlayerInterface;

class RegenHealthEffect implements GameEffectInterface
{
    protected PlayerInterface $player;
    protected int $intensity;

    public function __construct(PlayerInterface $player, int $intensity = 1)
    {
        $this->player = $player;
        $this->intensity = $intensity;
    }

    public function apply(): void
    {
        $this->player->increaseHealth($this->intensity);
    }
}