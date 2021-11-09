<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;
use Faker\Factory as FakerFactory;

class Dragon extends AbstractCreature
{
    protected int $armor = 40;
    protected int $damage = 15;
    protected int $health = 50;
    protected int $experience = 50;

    public function getName(): string
    {
        $faker = FakerFactory::create();

        return "Dragon - " . $faker->name;
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
        return new Gold(5 * $scale);
    }

    public function decreaseHealth(float $playerHitDamage)
    {
        $this->health = $this->health - $playerHitDamage;
    }
}