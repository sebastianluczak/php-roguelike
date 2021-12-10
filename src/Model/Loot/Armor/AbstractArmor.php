<?php

namespace App\Model\Loot\Armor;

use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\AbstractLoot;

abstract class AbstractArmor extends AbstractLoot
{
    public function __construct()
    {
        parent::__construct();

        $this->weight = 10;
        $this->lootType = LootTypeEnum::ARMOR();
    }
}
