<?php

namespace App\Model\Loot;

use App\Enum\Loot\LootClassEnum;

interface LootInterface
{
    public function isArmor(): bool;
    public function isWeapon(): bool;
    public function getName(): string;
    public function getArmor(): int;
    public function getDamage(): int;
    public function getLootType(): string;
    public function setLootClass(LootClassEnum $lootClass = null);
    public function getLootClass(): LootClassEnum;
}