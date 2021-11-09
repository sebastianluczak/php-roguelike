<?php

namespace App\Model\Tile;

use App\Model\Loot\Gold;
use App\Model\Loot\Weapon\Shield;
use App\Model\Loot\Weapon\Sword;

class RareChestTile extends AbstractTile
{
    public function isInteractable(): bool
    {
        return true;
    }

    public function isPassable(): bool
    {
        return true;
    }

    public function isSpawn(): bool
    {
        return false;
    }

    public function hasLogic(): bool
    {
        return true;
    }

    public function handleLogic()
    {
        $roll = random_int(0, 1000);
        if ($roll >= 400) {
            return new Sword();
        } else {
            return new Shield();
        }
    }

    public function draw(): string
    {
        return "#";
    }
}