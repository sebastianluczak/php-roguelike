<?php

namespace App\Model\Loot;

use App\Enum\Loot\LootClassEnum;

interface LootInterface
{
    public function isArmor(): bool;
    public function isWeapon(): bool;
    public function getName(): string;
    public function getLootType(): string;
    public function setLootType(string $lootType): AbstractLoot;
    public function setLootClass(LootClassEnum $lootClass = null);
    public function getLootClass(): LootClassEnum;
    public function getLootPickupMessage(): string;
    public function getDice(): string;
    public function isBetterThan(LootInterface $otherLoot): bool;
    public function getMinRollValue(): int; // @todo used for critical misses
    public function getMaxRollValue(): int; // @todo used for critical hits
    public function getAverageRoll(): float;
}