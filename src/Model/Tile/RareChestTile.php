<?php

namespace App\Model\Tile;

use App\Model\Loot\Gold;
use App\Model\Loot\Armor\Shield;
use App\Model\Loot\Weapon\Sword;
use App\Model\Stats\StatsInterface;
use App\Model\Tile\TileLogic\RareChestTileLogic;
use App\Model\Tile\TileLogic\TileLogicInterface;

class RareChestTile extends AbstractTile
{
    public function isInteractable(): bool
    {
        return false;
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
        return new RareChestTileLogic($scale, $stats);
    }

    public function draw(): string
    {
        return "<fg=bright-white>#</>";
    }
}
