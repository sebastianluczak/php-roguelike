<?php

namespace App\Model\Loot\Potion;

use App\Enum\GameIconEnum;
use App\Enum\Loot\LootTypeEnum;
use App\Model\Loot\AbstractLoot;

abstract class AbstractPotion extends AbstractLoot
{
    public function __construct()
    {
        parent::__construct();

        $this->lootType = LootTypeEnum::POTION();
    }

    public function __toString(): string
    {
        return sprintf(
            "%s %s",
            GameIconEnum::POTION(),
            $this->getName()
        );
    }
}
