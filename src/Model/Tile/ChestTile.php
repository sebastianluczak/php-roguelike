<?php

namespace App\Model\Tile;

use App\Model\Loot\Gold;
use App\Model\Stats\StatsInterface;
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

    public function handleLogic(int $scale, StatsInterface $stats): TileLogicInterface
    {
        return new ChestTileLogic($scale);
    }

    public function draw(): string
    {
        return "<fg=bright-white>#</>";
    }
}
