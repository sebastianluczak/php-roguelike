<?php

namespace App\Model\Loot;

use App\Enum\Loot\LootClassEnum;

abstract class AbstractLoot implements LootInterface
{
    protected string $dice;
    protected string $name;
    protected LootClassEnum $lootClass;
    protected $isWeapon;
    protected $isArmor;
    protected string $lootType;

    public function getArmor(): int
    {
        return 0;
    }

    public function getDamage(): int
    {
        return 0;
    }

    public function getLootType(): string
    {
        return $this->lootType;
    }

    public function getLootClass(): LootClassEnum
    {
        return $this->lootClass;
    }

    public function setLootClass(LootClassEnum $lootClass = null)
    {
        if (!$lootClass) {
            $this->lootClass = LootClassEnum::D();
        } else {
            $this->lootClass = $lootClass;
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setLootType(string $lootType): AbstractLoot
    {
        $this->lootType = $lootType;

        return $this;
    }
}