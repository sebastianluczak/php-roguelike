<?php

namespace App\Model\Tile;

use App\Model\Loot\Gold;

class ChestTile extends AbstractTile
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

    public function handleLogic(int $mapLevel)
    {
        $scale = 1 + $mapLevel * 0.1;

        return new Gold($scale);
    }

    public function draw(): string
    {
        return "#";
    }
}