<?php

namespace App\Model\Player\Inventory;

use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\LootInterface;
use Doctrine\Common\Collections\ArrayCollection;

interface InventoryBagInterface
{
    public function addItem(LootInterface $item): InventoryBagInterface;
    public function removeItem(LootInterface $item): InventoryBagInterface;

    public function getItemsOfType(LootTypeEnum $lootTypeEnum): array;
    public function count(): int;

    public function getItems(): ArrayCollection;
}