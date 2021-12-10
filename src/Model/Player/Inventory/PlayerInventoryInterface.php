<?php

namespace App\Model\Player\Inventory;

use App\Model\Loot\LootInterface;

interface PlayerInventoryInterface
{
    public function getWeaponSlot(): LootInterface;

    public function getSlotOfType(string $lootTypeEnum): LootInterface;

    public function getArmorSlot(): LootInterface;

    public function getKeystone(): LootInterface;

    public function handleLoot(LootInterface $loot): PlayerInventoryInterface;

    public function hasChanged(): bool;

    public function getInventoryBag(): InventoryBagInterface;

    public function addGoldAmount(int $amount): PlayerInventoryInterface;

    public function subtractGoldAmount(int $amount): PlayerInventoryInterface;

    public function getGoldAmount(): int;
}
