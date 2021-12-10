<?php

namespace App\Model\Loot\Keystone;

use App\Model\Stats\Stats;

class ChromaticKeystone extends AbstractKeystone
{
    protected string $name = 'Chromatic keystone';

    public function __construct(Stats $stats)
    {
        parent::__construct();

        $this->lootPickupMessage = "You've picked up ".$this->getFormattedName();

        $this->dice = '2d'.random_int(1, $stats->getIntelligence() + 1).'+'.$stats->getLuck();
    }
}
