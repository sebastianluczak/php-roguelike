<?php

namespace App\Model\Loot;

use App\Enum\Loot\LootClassEnum;
use DiceBag\DiceBag;
use Irfa\Gatcha\Roll;

abstract class AbstractLoot implements LootInterface
{
    protected string $name;
    protected LootClassEnum $lootClass;
    protected string $lootType;
    protected string $dice;
    protected string $lootPickupMessage;

    public function __construct()
    {
        $lootClassChances = [
            LootClassEnum::S()->getKey() => 0.5,
            LootClassEnum::A()->getKey() => 1,
            LootClassEnum::B()->getKey() => 2,
            LootClassEnum::C()->getKey() => 10,
            LootClassEnum::D()->getKey() => 60
        ];

        $lootClassRoll = Roll::put($lootClassChances)->spin();
        $this->lootClass = LootClassEnum::$lootClassRoll();
    }

    public function getLootType(): string
    {
        return $this->lootType;
    }

    public function getDice(): string
    {
        return $this->dice;
    }

    public function getLootClass(): LootClassEnum
    {
        return $this->lootClass;
    }

    public function setLootClass(LootClassEnum $lootClass = null)
    {
        if (!$lootClass) {
            $this->lootClass = LootClassEnum::D();
        } else {
            $this->lootClass = $lootClass;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setLootType(string $lootType): AbstractLoot
    {
        $this->lootType = $lootType;

        return $this;
    }

    public function getLootPickupMessage(): string
    {
        return $this->lootPickupMessage;
    }

    public function isBetterThan(LootInterface $otherLoot): bool
    {
        if ($this->getLootClass()->getValue() == $otherLoot->getLootClass()->getValue()) {
            if ($this->getAverageRoll() > $otherLoot->getAverageRoll()) {
                return true; // current loot ($self) IS better than $otherLoot
            }
        }
        if ($this->getLootClass()->getValue() < $otherLoot->getLootClass()->getValue()) {
            return true; // current loot ($self) IS better than $otherLoot
        }

        return false; // $otherLoot is better than current loot ($self)
    }

    public function getMaxRollValue(): int
    {
        $maxRoll = 0;
        $diceBag = DiceBag::factory($this->getDice());
        foreach ($diceBag->getDicePools() as $dicePool) {
            foreach ($dicePool->getDice() as $dice) {
                $maxRoll += $dice->max();
            }
        }

        return $maxRoll;
    }

    public function getMinRollValue(): int
    {
        $minRoll = 0;
        $diceBag = DiceBag::factory($this->getDice());
        foreach ($diceBag->getDicePools() as $dicePool) {
            foreach ($dicePool->getDice() as $dice) {
                $minRoll += $dice->min();
            }
        }

        return $minRoll;
    }

    public function getAverageRoll(): float
    {
        return ($this->getMinRollValue() + $this->getMaxRollValue()) / 2;
    }
}