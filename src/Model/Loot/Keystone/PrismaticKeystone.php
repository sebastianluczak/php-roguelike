<?php

namespace App\Model\Loot\Keystone;

use App\Model\Stats\Stats;

class PrismaticKeystone extends AbstractKeystone
{
    protected string $name = 'Prismatic keystone';

    public function __construct(Stats $stats)
    {
        parent::__construct();

        $this->lootPickupMessage = "You've picked up ".$this->getFormattedName();

        $this->dice = '1d'.random_int(1, $stats->getIntelligence()).'+'.$stats->getLuck();
    }
}
