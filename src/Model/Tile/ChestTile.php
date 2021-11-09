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

    public function handleLogic()
    {
        return new Gold();
    }

    public function draw(): string
    {
        return "#";
    }
}