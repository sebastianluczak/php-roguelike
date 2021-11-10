<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;

class Imp extends AbstractCreature
{
    protected int $armor = 0;
    protected int $damage = 1;
    protected int $health = 5;
    protected int $experience = 5;

    public function getName(): string
    {
        return "Imp";
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function getArmor(): int
    {
        return $this->armor;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function handleLoot(float $scale = 1)
    {
        return new Gold($scale);
    }

    public function decreaseHealth(float $playerHitDamage)
    {
        $this->health = $this->health - $playerHitDamage;
    }
}