<?php

namespace App\Model\Loot\Potion;

use App\Enum\GameIconEnum;
use App\Model\Stats\Stats;

class HealthPotion extends AbstractPotion
{
    protected string $name = 'Health potion';

    public function __construct(Stats $stats)
    {
        parent::__construct();

        $this->lootPickupMessage = "You've picked up ".GameIconEnum::POTION().' '.$this->getName();
    }
}
