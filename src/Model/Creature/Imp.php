<?php

namespace App\Model\Creature;

use App\Model\Loot\Gold;
use Faker\Factory as FakerFactory;
use Faker\Generator;

class Imp extends AbstractCreature
{
    protected int $armor = 0;
    protected int $damage = 1;
    protected int $health = 5;
    protected int $experience = 5;
    protected int $scale = 1;
    protected Generator $faker;

    public function __construct(float $scale)
    {
        $this->scale = $scale;
        $this->faker = FakerFactory::create('en_EN');
    }

    public function getName(): string
    {
        return "Imp - " . $this->faker->name;
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
        return new Gold($this->scale);
    }

    public function decreaseHealth(float $playerHitDamage)
    {
        $this->health = $this->health - $playerHitDamage;
    }
}