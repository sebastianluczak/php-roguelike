<?php

namespace App\Model\Loot\Weapon;

use App\Enum\Loot\LootClassEnum;
use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\AbstractLoot;

abstract class AbstractWeapon extends AbstractLoot
{
    public function __construct()
    {
        $this->lootType = LootTypeEnum::WEAPON();
        $this->lootClass = LootClassEnum::D();
    }

    public function isWeapon(): bool
    {
        return true;
    }

    public function isArmor(): bool
    {
        return false;
    }
}