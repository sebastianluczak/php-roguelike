<?php

namespace App\Model\Loot\Weapon;

use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\AbstractLoot;

abstract class AbstractWeapon extends AbstractLoot
{
    public function __construct()
    {
        parent::__construct();

        $this->weight = 5;
        $this->lootType = LootTypeEnum::WEAPON();
    }
}
