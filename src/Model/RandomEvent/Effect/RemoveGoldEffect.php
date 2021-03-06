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
        $this->player->getInventory()->subtractGoldAmount(random_int((int) ceil($this->player->getInventory()->getGoldAmount() / 10), $this->player->getInventory()->getGoldAmount()));
    }
}
