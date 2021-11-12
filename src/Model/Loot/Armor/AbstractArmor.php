<?php

namespace App\Model\Loot\Armor;

use App\Model\Loot\AbstractLoot;

abstract class AbstractArmor extends AbstractLoot
{
    public function isWeapon(): bool
    {
        return false;
    }

    public function isArmor(): bool
    {
        return true;
    }
}