<?php

namespace App\Model\Loot\Weapon;

use App\Model\Stats\Stats;

class Sword extends AbstractWeapon
{
    protected int $damage;

    public function __construct(Stats $playerStats)
    {
        parent::__construct();

        $this->damage = random_int(1, 3 + $playerStats->getLuck());
    }

    /**
     * @return int
     */
    public function getDamage(): int
    {
        return $this->damage;
    }

    public function getName(): string
    {
        return "Sword";
    }
}