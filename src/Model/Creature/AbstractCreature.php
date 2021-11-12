<?php

namespace App\Model\Creature;

use App\Model\Stats\Stats;
use App\Model\Stats\StatsInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;

abstract class AbstractCreature implements CreatureInterface
{
    protected int $health;
    protected int $damage;
    protected int $armor;
    protected string $name;
    protected int $experience;
    protected int $scale;
    protected Generator $faker;
    protected string $rawName;
    protected StatsInterface $stats;

    public function __construct()
    {
        $this->faker = FakerFactory::create('ja_JP');
        $this->rawName = $this->faker->kanaName;
        $this->stats = new Stats();
    }

    public function getExperience(): int
    {
        return $this->experience;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDamage(): int
    {
        return $this->damage;
    }

    public function getArmor(): int
    {
        if ($this->armor >= 80) {
            return 80;
        }

        return $this->armor;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function decreaseHealth(int $playerHitDamage)
    {
        $this->health = $this->health - $playerHitDamage;
    }

    public function getScale(): int
    {
        return $this->scale;
    }

    public function getRawName()
    {
        return $this->rawName;
    }

    public function createRandomNumberInRangeWithScale(int $min, int $max, int $scale): int
    {
        return $this->faker->numberBetween($min * $scale, $max * $scale);
    }

    public function getStats(): StatsInterface
    {
        return $this->stats;
    }
}