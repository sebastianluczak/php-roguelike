<?php

declare(strict_types=1);

namespace App\Model\Creature;

use App\Enum\Creature\CreatureClassEnum;
use App\Model\Loot\LootInterface;
use App\Model\Player\Inventory\InventoryBag;
use App\Model\Player\Inventory\InventoryBagInterface;
use App\Model\Stats\StatsInterface;
use Faker\Factory as FakerFactory;
use Faker\Generator;
use Irfa\Gatcha\Roll;
use RPGFaker\RPGFaker;

abstract class AbstractCreature implements CreatureInterface
{
    protected int $health;
    protected string $name;
    protected int $experience;
    protected int $scale;
    protected Generator $faker;
    protected string $rawName;
    protected StatsInterface $stats;
    protected LootInterface $weaponSlot;
    protected LootInterface $armorSlot;
    protected InventoryBagInterface $loot;
    protected CreatureClassEnum $creatureClass;
    protected float $initiative;

    public function __construct()
    {
        $classEnumChances = [
            CreatureClassEnum::NORMAL()->getKey() => 30,
            CreatureClassEnum::ELITE()->getKey() => 2,
            CreatureClassEnum::LEGENDARY()->getKey() => 1,
        ];

        $classEnumChancesSpin = Roll::put($classEnumChances)->spin();
        $this->creatureClass = CreatureClassEnum::$classEnumChancesSpin();

        $nameFaker = new RPGFaker(['count' => 1]);
        $this->faker = FakerFactory::create('ja_JP');
        $this->loot = new InventoryBag();
        if (is_string($generatedName = $nameFaker->name)) {
            $this->rawName = $generatedName;
        }
    }

    public function getExperience(): int
    {
        return $this->experience;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getHealth(): int
    {
        return $this->health;
    }

    public function decreaseHealth(int $playerHitDamage): void
    {
        $this->health = $this->health - $playerHitDamage;
    }

    public function getScale(): int
    {
        return $this->scale;
    }

    public function getRawName(): string
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

    public function getWeaponSlot(): LootInterface
    {
        return $this->weaponSlot;
    }

    public function getArmorSlot(): LootInterface
    {
        return $this->armorSlot;
    }

    public function getCreatureClass(): CreatureClassEnum
    {
        return $this->creatureClass;
    }

    public function getInitiative(): float
    {
        return $this->getStats()->getPerception() * $this->getStats()->getPerception() - $this->getStats()->getPerception() - $this->getScale();
    }
}
