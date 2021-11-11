<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;
use Faker\Factory as FakerFactory;
use Faker\Generator;

class Dragon extends AbstractCreature
{
    protected float $scale = 1;
    protected int $armor = 40;
    protected int $damage = 15;
    protected int $health = 50;
    protected int $experience = 50;
    protected Generator $faker;

    public function __construct(float $scale)
    {
        $this->scale = $scale;
        $this->faker = FakerFactory::create();
    }

    public function getName(): string
    {
        return "Dragon - " . $this->faker->name;
    }

    public function getDamage(): int
    {
        return $this->damage * $this->scale;
    }

    public function getArmor(): int
    {
        return $this->armor * $this->scale;
    }

    public function getHealth(): int
    {
        return $this->health * $this->scale;
    }

    public function handleLoot()
    {
        return new Gold(3 * $this->scale);
    }

    public function decreaseHealth(float $playerHitDamage)
    {
        $this->health = $this->health - $playerHitDamage;
    }
}