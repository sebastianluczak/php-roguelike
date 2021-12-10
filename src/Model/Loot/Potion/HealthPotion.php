<?php

namespace App\Model\Loot\Potion;

use App\Enum\GameIconEnum;

class HealthPotion extends AbstractPotion
{
    protected string $name = 'Health potion';

    public function __construct()
    {
        parent::__construct();

        $this->weight = 1;
        $this->lootPickupMessage = "You've picked up ".GameIconEnum::POTION().' '.$this->getName();
    }
}
