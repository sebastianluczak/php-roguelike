<?php

namespace App\Model\Loot;

use App\Enum\Loot\LootClassEnum;
use DiceBag\DiceBag;
use Irfa\Gatcha\Roll;

abstract class AbstractLoot implements LootInterface
{
    protected int $amount = 1;
    protected int $weight = 1;
    protected string $name = '[WIP][ITEM][NO_NAMED]';
    protected LootClassEnum $lootClass;
    protected string $lootType;
    protected string $dice = '1d1'; // TODO sort out all the dices and make some accessor for them
    protected string $lootPickupMessage;
    protected int $priceValue = 1;

    public function __construct()
    {
        $lootClassChances = [
            LootClassEnum::S()->getKey() => 0.5,
            LootClassEnum::A()->getKey() => 1,
            LootClassEnum::B()->getKey() => 2,
            LootClassEnum::C()->getKey() => 10,
            LootClassEnum::D()->getKey() => 60,
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

    public function setLootClass(LootClassEnum $lootClass = null): void
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
        if ($this->getAverageRoll() > $otherLoot->getAverageRoll()) {
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

    public function getPriceValue(): int
    {
        return $this->priceValue;
    }

    public function getFormattedName(): string
    {
        return $this->name;
    }

    public function getAmount(): int
    {
        return $this->amount;
    }

    public function getWeight(): int
    {
        return $this->weight;
    }
}
