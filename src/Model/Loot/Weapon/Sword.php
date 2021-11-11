<?php

namespace App\Model\Loot\Weapon;

use App\Model\Loot\AbstractLoot;

class Sword extends AbstractLoot
{
    protected int $damage;

    public function __construct()
    {
        $this->damage = random_int(1, 3);
    }

    /**
     * @return int
     */
    public function getDamage(): int
    {
        return $this->damage;
    }

    public function isArmor(): bool
    {
        return false;
    }

    public function isWeapon(): bool
    {
        return true;
    }

    public function getName(): string
    {
        return "Sword";
    }
}