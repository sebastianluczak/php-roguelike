<?php

namespace App\Model\Loot\Keystone;

use App\Enum\GameIconEnum;
use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\AbstractLoot;

abstract class AbstractKeystone extends AbstractLoot
{
    public function __construct()
    {
        parent::__construct();

        $this->lootType = LootTypeEnum::KEYSTONE();
    }

    public function isWeapon(): bool
    {
        return false;
    }

    public function isArmor(): bool
    {
        return false;
    }

    public function __toString(): string
    {
        return sprintf(
            "%s %s",
            GameIconEnum::GEM(),
            $this->getName()
        );
    }
}