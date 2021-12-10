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

    // fixme get rid of __toString implementation
    public function __toString(): string
    {
        return sprintf(
            '%s %s',
            GameIconEnum::GEM(),
            $this->getName()
        );
    }
}
