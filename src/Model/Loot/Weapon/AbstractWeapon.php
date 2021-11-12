<?php

namespace App\Model\Loot\Weapon;

use App\Model\Loot\AbstractLoot;

abstract class AbstractWeapon extends AbstractLoot
{
    public function isWeapon(): bool
    {
        return true;
    }

    public function isArmor(): bool
    {
        return false;
    }
}