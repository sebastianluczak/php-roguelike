<?php

namespace App\Model\Player\Inventory;

use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\Armor\Shield;
use App\Model\Loot\LootInterface;
use App\Model\Loot\Weapon\Sword;
use App\Model\Stats\Stats;

class PlayerInventory implements PlayerInventoryInterface
{
    protected LootInterface $weaponSlot;
    protected LootInterface $armorSlot;
    protected LootInterface $keyStone;

    public function __construct(Stats $stats)
    {
        $this->weaponSlot = new Sword($stats);
        // todo luckModifier from stats, do it other way, factory?
        $this->armorSlot = new Shield($stats);
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

    // todo wip
    // add checks if user wants to switch weapons?
    public function handleLoot(LootInterface $loot)
    {
        switch ($loot->getLootType()) {
            case LootTypeEnum::WEAPON():
                $this->weaponSlot = $loot;
                break;
            case LootTypeEnum::ARMOR():
                $this->armorSlot = $loot;
                break;
            case LootTypeEnum::KEYSTONE():
                $this->keyStone = $loot;
                break;
        }
    }
}