<?php

namespace App\Model\Loot\Weapon;

class Sword extends AbstractWeapon
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

    public function getName(): string
    {
        return "Sword";
    }
}