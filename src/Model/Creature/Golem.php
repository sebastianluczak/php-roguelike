<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;

class Golem extends AbstractCreature
{
    protected int $armor = 10;
    protected int $damage = 5;
    protected int $health = 15;
    protected int $experience = 20;

    public function getName(): string
    {
        return "Golem";
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

    public function handleLoot()
    {
        return new Gold(1.5);
    }

    public function decreaseHealth(float $playerHitDamage)
    {
        $this->health = $this->health - $playerHitDamage;
    }
}