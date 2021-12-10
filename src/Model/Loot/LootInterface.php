<?php

namespace App\Model\Loot;

use App\Enum\Loot\LootClassEnum;

interface LootInterface
{
    public function getName(): string;

    public function getLootType(): string;

    public function setLootType(string $lootType): AbstractLoot;

    public function setLootClass(LootClassEnum $lootClass = null);

    public function getLootClass(): LootClassEnum;

    public function getLootPickupMessage(): string;

    public function getDice(): string;

    public function isBetterThan(LootInterface $otherLoot): bool;

    public function getMinRollValue(): int;

    public function getMaxRollValue(): int;

    public function getAverageRoll(): float;

    public function getPriceValue(): int;

    public function getFormattedName(): string;

    public function getAmount(): int;
}
