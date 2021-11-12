<?php

namespace App\Model\Player\Inventory;

use App\Model\Loot\LootInterface;

interface PlayerInventoryInterface
{
    public function getWeaponSlot(): LootInterface;
    public function getArmorSlot(): LootInterface;
    public function getKeystone(): LootInterface;
    public function handleLoot(LootInterface $loot);
}