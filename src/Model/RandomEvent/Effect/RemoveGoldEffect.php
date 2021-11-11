<?php

namespace App\Model\RandomEvent\Effect;

use App\Model\Player\PlayerInterface;

class RemoveGoldEffect implements GameEffectInterface
{
    protected PlayerInterface $player;

    public function __construct(PlayerInterface $player)
    {
        $this->player = $player;
    }

    public function apply(): void
    {
        $this->player->decreaseGoldAmount(random_int(ceil($this->player->getGold() / 10), $this->player->getGold()));
    }
}