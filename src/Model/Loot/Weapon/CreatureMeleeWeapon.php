<?php

namespace App\Model\Loot\Weapon;

use App\Model\Stats\Stats;

class CreatureMeleeWeapon extends AbstractWeapon
{
    protected string $name = "Claw";

    public function __construct(Stats $stats)
    {
        parent::__construct();
        $this->dice = '3d' . random_int(1, $stats->getStrength()) . '+' . $stats->getLuck();
    }
}