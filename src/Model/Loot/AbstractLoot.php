<?php

namespace App\Model\Loot;

abstract class AbstractLoot implements LootInterface
{
    protected $isWeapon;
    protected $isArmor;

    public function getArmor(): int
    {
        return 0;
    }

    public function getDamage(): int
    {
        return 0;
    }
}