<?php

namespace App\Model\Tile;

use App\Model\Loot\Gold;
use App\Model\Tile\TileLogic\ChestTileLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

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

    public function handleLogic(int $mapLevel): TileLogicInterface
    {
        return new ChestTileLogic($mapLevel);
    }

    public function draw(): string
    {
        return "#";
    }
}