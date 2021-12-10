<?php

namespace App\Model\Player\Inventory;

use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\LootInterface;
use App\Model\Loot\Potion\HealthPotion;
use Doctrine\Common\Collections\ArrayCollection;

class InventoryBag implements InventoryBagInterface
{
    protected ArrayCollection $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();

        $this->addItem(new HealthPotion());
        $this->addItem(new HealthPotion());
    }

    public function addItem(LootInterface $item): InventoryBagInterface
    {
        $this->items->add($item);

        return $this;
    }

    public function removeItem(LootInterface $item): InventoryBagInterface
    {
        $this->items->removeElement($item);

        return $this;
    }

    /**
     * @return array|LootInterface[]
     */
    public function getItemsOfType(LootTypeEnum $lootTypeEnum): array
    {
        $searchedItems = $this->items->filter(function (LootInterface $loot) use ($lootTypeEnum) {
            return $loot->getLootType() == $lootTypeEnum->getValue();
        });

        return $searchedItems->toArray();
    }

    public function count(): int
    {
        return $this->items->count();
    }

    public function getItems(): ArrayCollection
    {
        return $this->items;
    }
}
