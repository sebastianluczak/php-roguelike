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
    protected LootInterface $weaponSlot;
    protected LootInterface $armorSlot;
    protected LootInterface $keyStone;
    protected bool $hasChanged;

    public function __construct(Stats $stats)
    {
        $this->hasChanged = false;
        $this->weaponSlot = new Sword($stats);
        // todo luckModifier from stats, do it other way, factory?
        $this->armorSlot = new Shield($stats);
        $this->keyStone = new BrokenKeystone($stats);
    }

    public function getWeaponSlot(): LootInterface
    {
        return $this->weaponSlot;
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
                }
                break;
            case LootTypeEnum::ARMOR():
                if (!$this->armorSlot->isBetterThan($loot)) {
                    $this->armorSlot = $loot;
                    $this->hasChanged = true;
                }
                break;
            case LootTypeEnum::KEYSTONE():
                if (!$this->keyStone->isBetterThan($loot)) {
                    $this->keyStone = $loot;
                    $this->hasChanged = true;
                }
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
}