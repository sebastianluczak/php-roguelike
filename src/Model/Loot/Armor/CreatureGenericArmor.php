<?php

namespace App\Model\Loot\Armor;

use App\Model\Stats\Stats;

class CreatureGenericArmor extends AbstractArmor
{
    protected string $name = 'Scales';

    public function __construct(Stats $stats)
    {
        parent::__construct();
        $this->dice = '1d'.random_int(1, $stats->getEndurance()).'+'.$stats->getLuck();
    }

    public function getFormattedName(): string
    {
        return $this->name;
    }
}
