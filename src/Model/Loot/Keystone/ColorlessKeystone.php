<?php

namespace App\Model\Loot\Keystone;

use App\Enum\GameIconEnum;
use App\Model\Stats\Stats;

class ColorlessKeystone extends AbstractKeystone
{
    protected string $name = "Colorless keystone";

    public function __construct(Stats $stats)
    {
        parent::__construct();

        $this->lootPickupMessage = "You've picked up " . GameIconEnum::GEM() . " " . $this->getName();

        $this->dice = '5d' . random_int(1, $stats->getIntelligence() + 8) . '+' . $stats->getLuck();
    }
}
