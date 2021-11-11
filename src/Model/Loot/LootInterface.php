<?php

namespace App\Model\Loot;

interface LootInterface
{
    public function isArmor(): bool;
    public function isWeapon(): bool;
    public function getName(): string;
    public function getArmor(): int;
    public function getDamage(): int;
}