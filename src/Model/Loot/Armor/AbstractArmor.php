<?php

namespace App\Model\Loot\Armor;

use App\Enum\Loot\LootClassEnum;
use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\AbstractLoot;

abstract class AbstractArmor extends AbstractLoot
{
    public function __construct()
    {
        $this->lootType = LootTypeEnum::ARMOR();
        $this->lootClass = LootClassEnum::D();
    }

    public function isWeapon(): bool
    {
        return false;
    }

    public function isArmor(): bool
    {
        return true;
    }
}