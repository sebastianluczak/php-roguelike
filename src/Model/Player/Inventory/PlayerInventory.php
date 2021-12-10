<?php

namespace App\Model\Player\Inventory;

use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\Armor\Shield;
use App\Model\Loot\Keystone\BrokenKeystone;
use App\Model\Loot\LootInterface;
use App\Model\Loot\Weapon\Sword;
use App\Model\Stats\Stats;

class PlayerInventory implements PlayerInventoryInterface
{
    protected InventoryBagInterface $inventoryBag;
    /** @var LootInterface|Sword */
    protected LootInterface $weaponSlot;
    protected LootInterface $armorSlot;
    protected LootInterface $keyStone;
    protected bool $hasChanged;
    protected int $goldAmount;

    public function __construct()
    {
        $stats = new Stats();
        $this->hasChanged = false;
        $this->goldAmount = $stats->getIntelligence() * 10;
        $this->weaponSlot = new Sword($stats);
        $this->armorSlot = new Shield($stats);
        $this->keyStone = new BrokenKeystone();
        $this->inventoryBag = new InventoryBag();
    }

    public function getWeaponSlot(): LootInterface
    {
        return $this->weaponSlot;
    }

    public function getSlotOfType(string $lootTypeEnum): LootInterface
    {
        switch ($lootTypeEnum) {
            case LootTypeEnum::WEAPON():
                return $this->weaponSlot;
            case LootTypeEnum::ARMOR():
                return $this->armorSlot;
            case LootTypeEnum::KEYSTONE():
                return $this->keyStone;
        }

        return $this->inventoryBag->getItemsOfType(LootTypeEnum::from($lootTypeEnum))[0];
    }

    public function getArmorSlot(): LootInterface
    {
        return $this->armorSlot;
    }

    public function getKeystone(): LootInterface
    {
        return $this->keyStone;
    }

    // todo check if user wants to switch weapons? kinda hard
    public function handleLoot(LootInterface $loot): PlayerInventoryInterface
    {
        switch ($loot->getLootType()) {
            case LootTypeEnum::WEAPON():
                if (!$this->weaponSlot->isBetterThan($loot)) {
                    $this->weaponSlot = $loot;
                    $this->hasChanged = true;
                } else {
                    $this->inventoryBag->addItem($loot);
                }
                break;
            case LootTypeEnum::ARMOR():
                if (!$this->armorSlot->isBetterThan($loot)) {
                    $this->armorSlot = $loot;
                    $this->hasChanged = true;
                } else {
                    $this->inventoryBag->addItem($loot);
                }
                break;
            case LootTypeEnum::KEYSTONE():
                if (!$this->keyStone->isBetterThan($loot)) {
                    $this->keyStone = $loot;
                    $this->hasChanged = true;
                } else {
                    $this->inventoryBag->addItem($loot);
                }
                break;
            case LootTypeEnum::POTION():
                $this->inventoryBag->addItem($loot);
                break;
            case LootTypeEnum::GOLD():
                $this->addGoldAmount($loot->getAmount());
                break;
        }

        return $this;
    }

    public function hasChanged(): bool
    {
        $currentStatusHasChanged = $this->hasChanged;
        $this->hasChanged = false;

        return $currentStatusHasChanged;
    }

    /**
     * @return InventoryBag|InventoryBagInterface
     */
    public function getInventoryBag(): InventoryBagInterface
    {
        return $this->inventoryBag;
    }

    public function addGoldAmount(int $amount): PlayerInventoryInterface
    {
        $this->goldAmount = $this->getGoldAmount() + $amount;

        return $this;
    }

    public function subtractGoldAmount(int $amount): PlayerInventoryInterface
    {
        $this->goldAmount = $this->getGoldAmount() - $amount;

        return $this;
    }

    public function getGoldAmount(): int
    {
        return $this->goldAmount;
    }
}
