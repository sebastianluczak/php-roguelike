<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;
use Faker\Factory as FakerFactory;
use Faker\Generator;

class Golem extends AbstractCreature
{
    protected int $armor = 10;
    protected int $damage = 5;
    protected int $health = 15;
    protected int $experience = 20;
    protected float $scale = 1;
    protected Generator $faker;

    public function __construct(float $scale)
    {
        $this->scale = $scale;
        $this->faker = FakerFactory::create('en_EN');
    }

    public function getName(): string
    {
        return "Golem - " . $this->faker->name;
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
        return new Gold(1.5 * $this->scale);
    }

    public function decreaseHealth(float $playerHitDamage)
    {
        $this->health = $this->health - $playerHitDamage;
    }
}