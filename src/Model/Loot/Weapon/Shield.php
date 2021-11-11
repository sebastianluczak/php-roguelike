<?php

namespace App\Model\Loot\Weapon;

use App\Model\Loot\AbstractLoot;

class Shield extends AbstractLoot
{
    protected int $armor;

    public function __construct()
    {
        $this->armor = random_int(1, 3);
    }

    /**
     * @return int
     */
    public function getArmor(): int
    {
        return $this->armor;
    }

    public function isArmor(): bool
    {
        return true;
    }

    public function isWeapon(): bool
    {
        return false;
    }

    public function getName(): string
    {
        return "Shield";
    }
}